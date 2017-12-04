<?php

namespace App\Http\Controllers\Creator;

use App\ScheduledPost;
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
        $scheduled_posts = auth()->user()->scheduledPosts()->where('status', '<>', 'Pendiente')->get();

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
        // https://stackoverflow.com/questions/37777265/required-if-laravel-5-validation
        $rules = [
            'type' => 'required',
            'link' => 'required_if:type,==,link',
            'image_url' => 'required_if:type,==,image',
            'video_url' => 'required_if:type,==,video'
        ];
        $messages = [
            'type.required' => 'Es necesario seleccionar un tipo de publicación.',
            'link.required_if' => 'Es necesario ingresar el enlace que se va a compartir.',
            'image_url.required_if' => 'Es necesario ingresar la URL de la imagen a compartir.',
            'video_url.required_if' => 'Es necesario ingresar la URL del vide a compartir.'
        ];
        $this->validate($request, $rules, $messages);
        // dd($request->all());
        $scheduled_post = new ScheduledPost();
        $scheduled_post->type = $request->input('type');
        $scheduled_post->link = $request->input('link');
        $scheduled_post->image_url = $request->input('image_url');
        $scheduled_post->video_url = $request->input('video_url');
        $scheduled_post->description = $request->input('description');
        $scheduled_post->scheduled_date = $request->input('scheduled_date');
        $scheduled_post->scheduled_time = $request->input('scheduled_time');
        $scheduled_post->user_id = auth()->id();
        $scheduled_post->fb_destination_id = '948507005305322'; // temporary constant value
        $scheduled_post->status = 'Pendiente';
        $scheduled_post->save();

        $notification = 'Se ha registrado una nueva publicación programada.';
        return back()->with(compact('notification'));
    }

    public function destroy(Request $request)
    {
        $this->validate($request, [
            'post_id' => 'required|exists:scheduled_posts,id'
        ]);

        $post = ScheduledPost::findOrFail($request->input('post_id'));
        if ($post->user_id == auth()->user()->id)
            $deleted = $post->delete();

        if ($deleted)
            $notification = "Se ha eliminado exitosamente la publicación programada # $post->id.";

        return back()->with(compact('notification'));
    }
}
