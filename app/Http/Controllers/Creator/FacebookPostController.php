<?php

namespace App\Http\Controllers\Creator;

use App\FanPage;
use App\ScheduledPost;
use App\ScheduledPostImage;
use App\User;
use Carbon\Carbon;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookPostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function askPassword()
    {
        return view('panel.posts.ask_password');
    }

    public function verifyPassword(Request $request)
    {
        $rules = [
            'pass' => 'in:Momy2018'
        ];
        $messages = [
            'pass.in' => 'La contraseña ingresada no es correcta.'
        ];
        $this->validate($request, $rules, $messages);

        $user = auth()->user();
        $user->entered_password_for_fb_posts = true;
        $user->save();

        $notification = 'Has ingresado correctamente la contraseña!';
        return redirect('/facebook/posts')->with(compact('notification'));
    }

    public function index()
    {
        $user = auth()->user();
        if (!$user->entered_password_for_fb_posts) // 0
            return redirect('/facebook/posts/password');

        // $availablePermissions = $this->checkAvailablePermissions($facebookSdk);


        // the posts will be performed using the access token of the group admin
        $scheduled_posts = $user->scheduledPosts()->where('status', 'Pendiente')->get();

        $finished_posts = $user->scheduledPosts()
            ->orderBy('scheduled_date', 'desc')->orderBy('scheduled_time', 'desc')
            ->where('status', '<>', 'Pendiente')->paginate(10);

        return view('panel.posts.index')
            ->with(compact('availablePermissions', 'scheduled_posts', 'finished_posts'));
    }

    private function checkAvailablePermissions(LaravelFacebookSdk $facebookSdk)
    {
        // $accessToken = auth()->user()->fb_access_token;
        $adminAuthor = User::where('email', 'tombofans@gmail.com')->first(['fb_access_token']);
        if (!$adminAuthor) // admin doesn't exist!
            return false;

        $accessToken = $adminAuthor->fb_access_token;

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

    public function create(LaravelFacebookSdk $facebookSdk)
    {
        $availablePermissions = $this->checkAvailablePermissions($facebookSdk);

        // current time +10 minutes
        $time = Carbon::now()->addMinutes(10)->format('H:i');

        $pages = auth()->user()->fanPages;

        return view('panel.posts.create')->with(compact('availablePermissions', 'time', 'pages'));
    }

    public function store(Request $request)
    {
        $rules = [
            'description' => 'required_without:imageUrls',
            'scheduled_time' => 'required_if:now,null',
            'scheduled_date' => 'required_if:now,null',

            'fan_page_id' => 'required_if:check_page,on',
            'contact_info' => 'required_if:check_other,on'
        ];
        $messages = [
            'description.required_without' => 'Es necesario ingresar una descripción.',
            'scheduled_time.required_if' => 'Debes seleccionar una hora de publicación.',
            'scheduled_date.required_if' => 'Debes seleccionar una fecha de publicación.',

            'fan_page_id.required_if' => 'Debes seleccionar una fanpage o desmarcar el check.',
            'contact_info.required_if' => 'Debes ingresar una información de contacto o desmarcar el check.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->after(function ($validator) use ($request) {
            if (! $request->has('now')) {
                if ($request->has('scheduled_date') && $request->input('scheduled_date') < date('Y-m-d'))
                    $validator->errors()->add('scheduled_date', 'No puedes enviar un mensaje al pasado!');
                // when it fails isn't necessary to check for the time
                elseif ($request->has('scheduled_time') && $request->input('scheduled_time') < date('H:i'))
                    $validator->errors()->add('scheduled_time', 'Por favor verifica la hora de envío.');
            }
        });

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $description = $request->input('description');
        $imagePostIds = $request->input('imageUrls');

        // new scheduled post
        $scheduled_post = new ScheduledPost();

        // Detect type of post based on params
        if ($imagePostIds) {
            $imagesQuantity = sizeof($imagePostIds);

            if ($imagesQuantity == 1) {
                $postType = 'image';
                // set image url
                $scheduled_post->image_url = ScheduledPostImage::find($imagePostIds[0])->secure_url;
            } else { // qty >= 2
                $postType = 'images';
            }
        } else { // no images detected
            $firstLink = firstLink($description);
            if ($firstLink) { // contains a link
                $postType = 'link';
                $scheduled_post->link = $firstLink;
            } else {
                $postType = 'text';
            }
        }

        // set attributes
        $scheduled_post->type = $postType;
        $scheduled_post->description = $description;
        $scheduled_post->user_id = auth()->id();

        if ($request->has('now') && $request->input('now')==1) {
            $scheduled_post->scheduled_date = date('Y-m-d');
            $scheduled_post->scheduled_time = date('H:i');
            $scheduled_post->status = 'En cola';
        } else {
            $scheduled_post->scheduled_date = $request->input('scheduled_date');
            $scheduled_post->scheduled_time = $request->input('scheduled_time');
            $scheduled_post->status = 'Pendiente';
        }

        /*
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
        */
        $scheduled_post = $this->buildContactInfo($scheduled_post, $request);
        $this->applyDelayIfNeeded($scheduled_post); // update scheduled date & time if needed
        $saved = $scheduled_post->save();

        // continue based on the post type

        if ($saved && $postType == 'images' || $postType == 'image') {
            ScheduledPostImage::whereIn('id', $imagePostIds)->update([
                'scheduled_post_id' => $scheduled_post->id // after save operation
            ]);
        }

        $data = [];
        $data['success'] = $saved;
        return $data;
    }

    public function buildContactInfo(ScheduledPost $post, Request $request)
    {
        $info = '';

        if ($request->has('check_page')) {
            $page = FanPage::find($request->input('fan_page_id'));
            if ($page) {
                $pageLink = 'https://fb.com/' . $page->fan_page_id;
                $info .= "\nPágina de contacto: $pageLink";
            }
        }

        if ($request->has('check_tag_user')) {
            $name = auth()->user()->name;
            $fbUserId = auth()->user()->facebook_user_id;
            $info .= "\nContactar a: $name https://fb.com/$fbUserId";
        }

        if ($request->has('check_other')) {
            $contactInfo = $request->input('contact_info');
            $info .= "\nMedio de contacto: $contactInfo";
        }

        $post->description = $post->description . $info;
        return $post;
    }

    public function applyDelayIfNeeded(ScheduledPost $post)
    {
        while (ScheduledPost::existsCollisionsWith($post)) {
            $newDateTime = $post->getScheduledDateTime()->addMinutes(2);
            $post->scheduled_date = $newDateTime->format('Y-m-d');
            $post->scheduled_time = $newDateTime->format('H:i');
        }
        $post->save();
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
