<?php

namespace App\Http\Controllers\Creator;

use App\ScheduledPost;
use App\ScheduledPostImage;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(LaravelFacebookSdk $facebookSdk)
    {
        $availablePermissions = $this->checkAvailablePermissions($facebookSdk);

        $scheduled_posts = auth()->user()->scheduledPosts()->where('status', 'Pendiente')->get();

        return view('panel.posts.index')
            ->with(compact('availablePermissions', 'scheduled_posts'));
    }

    private function checkAvailablePermissions(LaravelFacebookSdk $facebookSdk)
    {
        $accessToken = auth()->user()->fb_access_token;

        if ($accessToken) {
            try {
                $facebookSdk->setDefaultAccessToken($accessToken);
                $response = $facebookSdk->get('/me/permissions');
                $graphEdge = $response->getGraphEdge();
                $permissions = $graphEdge->all();

                $availablePermissions = false;
                foreach ($permissions as $permission) {
                    if ($permission['permission'] == 'publish_actions' && $permission['status'] == 'granted') {
                        $availablePermissions = true;
                        break;
                    }
                }

                return $availablePermissions;
            } catch (FacebookSDKException $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    public function finished()
    {
        $scheduled_posts = auth()->user()->scheduledPosts()
            ->orderBy('scheduled_date', 'desc')->orderBy('scheduled_time', 'desc')
            ->where('status', '<>', 'Pendiente')->paginate(10);

        return view('panel.posts.finished')
            ->with(compact('scheduled_posts'));
    }

    public function create(LaravelFacebookSdk $facebookSdk)
    {
        $availablePermissions = $this->checkAvailablePermissions($facebookSdk);
        return view('panel.posts.create')->with(compact('availablePermissions'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $rules = [
            'type' => 'required',
            'link' => 'required_if:type,==,link',
            'image_file' => 'required_if:type,==,image|image',
            'video_file' => 'required_if:type,==,video|mimes:mp4,mov,ogg,qt,wmv,asf,application/octet-stream|max:20000',
            'imageUrls' => 'required_if:type,==,images',
            'scheduled_time' => 'required',
            'scheduled_date' => 'required'
        ];
        $messages = [
            'scheduled_time.required' => 'Debe seleccionar una hora de publicación.',
            'scheduled_date.required' => 'Debe seleccionar una fecha de publicación.',
            'type.required' => 'Es necesario seleccionar un tipo de publicación.',
            'link.required_if' => 'Es necesario ingresar el enlace que se va a compartir.',
            'image_file.required_if' => 'Es necesario subir la imagen a compartir.',
            'image_file.image' => 'Debe subir una imagen válida.',
            'video_file.required_if' => 'Es necesario subir el video a compartir.',
            'video_file.mimes' => 'Debe subir un video válido.',
            'video_file.max' => 'El video supera el límite permitido (20000).',
            'imageUrls.required_if' => 'Debe subir al menos una imagen, o escoger otro tipo de publicación.',
        ];
        $this->validate($request, $rules, $messages);
        // dd($request->all());

        $postType = $request->input('type');

        $scheduled_post = new ScheduledPost();
        $scheduled_post->type = $postType;
        $scheduled_post->link = $request->input('link');
        $scheduled_post->description = $request->input('description');
        $scheduled_post->scheduled_date = $request->input('scheduled_date');
        $scheduled_post->scheduled_time = $request->input('scheduled_time');
        $scheduled_post->user_id = auth()->id();
        $scheduled_post->fb_destination_id = '948507005305322'; // temporary constant value
        $scheduled_post->status = 'Pendiente';

        // type: image
        if ($postType == 'image' && $request->hasFile('image_file')) {
            $response = PostCloudinaryFile::upload($request->file('image_file'), 'image');
            $scheduled_post->image_url = $response['secure_url'];
        }
        else
        // type: video
        if ($postType == 'video' && $request->hasFile('video_file')) {
            ini_set('max_execution_time', 300);
            $response = PostCloudinaryFile::upload($request->file('video_file'), 'video');
            $scheduled_post->video_url = $response['secure_url'];
        }
        $scheduled_post->save();

        // type: images
        if ($postType == 'images' && $request->has('imageUrls')) {
            ScheduledPostImage::whereIn('id', $request->input('imageUrls'))->update([
                'scheduled_post_id' => $scheduled_post->id // after save operation
            ]);
        }



        $notification = 'Se ha registrado una nueva publicación programada.';
        return redirect('facebook/posts')->with(compact('notification'));
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'post_id' => 'required|exists:scheduled_posts,id'
        ]);

        $post = ScheduledPost::findOrFail($request->input('post_id'));
        if ($post->user_id == auth()->user()->id) {
            // $post->images()->delete(); // a garbage collector will do this, if we delete the rows, we lost the cloudinary public id
            $deleted = $post->delete();
        }

        if ($deleted)
            $notification = "Se ha eliminado exitosamente la publicación programada # $post->id.";

        return back()->with(compact('notification'));
    }
}
