<?php

namespace App\Http\Controllers;

use App\Events\BroadCaseEvent;
use App\Events\BroadCastEvent2;
use App\Events\BroadCastEventAll;
use App\Http\Requests\MailRequest;
use App\Http\Requests\OnlineStatus;
use App\Mail\SendFormattedMail;
use App\Mail\TestMail;
use App\Models\MailTemplate;
use App\Models\QuoteTable;
use App\Models\TestMessage;
use App\Models\User;
use App\Repositary\Blog\BlogRepo;
use App\Repositary\CustomRepo\CustomRepo;
use App\Repositary\DineshCustom\DineshCustomRepo;
use Carbon\Carbon;
use Carbon\Exceptions\Exception;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Intervention\Image\Laravel\Facades\Image;

class HomeController extends Controller
{
    private $blogRepo, $customRepo;
    public function __construct(BlogRepo $blogRepo, CustomRepo $customRepo)
    {
        $this->blogRepo = $blogRepo;
        $this->customRepo = $customRepo;
        // $this->customRepo->logCustomRepo();
        // $this->blogRepo->writeLog();
    }
    public function index()
    {
        return view('indexAll');
    }
    public function indexMultiple()
    {
        return view('indexMultiple');
    }

    public function index1()
    {
        // app(DineshCustomRepo::class)->test();
        return view('index');
    }
    public function quote()
    {
        return view('quote');
    }

    public function index2()
    {
        return view('index2');
    }
    public function notification()
    {
        return view('notification');
    }

    public function event()
    {
        $data = ['message' => 'Hello, this is a test event!'];
        event(new BroadCaseEvent('test'));
        return response()->json(['status' => 'Event has been sent!']);
    }

    public function event2()
    {
        $data = ['message' => 'Hello, this is a test event!'];
        event(new BroadCastEvent2('test'));
        return response()->json(['status' => 'Event has been sent!']);
    }

    public function allEvent()
    {
        $data = ['message' => 'Hello, this is a test event!'];
        event(new BroadCastEventAll('test'));
        return response()->json(['status' => 'Event '.session()->get('count').' has been sent!']);
    }

    public function fakeLogin()
    {
        try {
            /**
             * 
             * USER CREATE
             */

            // User::create([
            //     'name' => 'dinesh',
            //     'email' => 'dinesh@yopmail.com',
            //     'password' => Hash::make('Admin1!')
            // ]);

            // User::create([
            //     'name' => 'baidya',
            //     'email' => 'baidya@yopmail.com',
            //     'password' => Hash::make('Admin1!')
            // ]);

            // return response()->json(['data' => 'User created.']);

            /** 
             * 
             * USER LOGIN
             */

            $data = [
                'email' => 'dinesh@yopmail.com',
                'password' => 'Admin1!'
            ];
            if(Auth::attempt($data)){
                return response()->json(['data' => 'Logged in']);
            }
            return response()->json(['data' => 'not logged in']);

            /** 
             * 
             * ADMIN LOGIN
             */
            // $data = [
            //     'email' => 'baidya@yopmail.com',
            //     'password' => 'Admin1!'
            // ];
            // if(Auth::guard('admin')->attempt($data)){
            //     return response()->json(['data' => 'admin Logged in']);
            // }
            // return response()->json(['data' => 'admin not logged in']);

            /** 
             * 
             * LOGOUT
             */
            // Auth::guard('admin')->logout();
            // Auth::logout();
            // return response()->json(['data' => 'logged out']);

            return response()->json(['data' => 'NO ACTION TO PERFORM']);
        } catch (UniqueConstraintViolationException $e) {
            return response()->json(['data' => 'The Email is already exist.']);
        }
    }

    public function uploadImage()
    {
        return view('upload_image');
    }

    public function doUploadImage(Request $request)
    {
        $cropImage = $request->file('croppedImage');
        $imageName = 'Image_' . (date('Y_m_d_H_i_s')) . '.' . $request->file('image')->getClientOriginalExtension();
        $img = uploadImgFile($cropImage, ['user', 'images'], $imageName, true, true, auth()->user());
        return response()->json(['success' => 2, 'msg' => 'Image uploaded and compressed successfully!', 'data' => $img['image_path'] ?? '']);
    }

    public function uploadFiles()
    {
        return view('upload_file');
    }

    public function doUploadFiles(Request $request)
    {
        $files = $request->file('files');
        if ($files == null) {
            return response()->json(['success' => 0, 'msg' => 'There is no files to Upload.']);
        }
        $data = upload_files($files, ['document', 'users', 'doc', 'pdf']);
        return response()->json(['success' => 0, 'msg' => 'Files Uploaded.', 'data' => $data]);
    }


    // public function test()
    // {
    //     $exitCode = Artisan::call('queue:work', [
    //         '--timeout' => 60,
    //         '--tries' => 3,
    //     ]);

    //     $output = Artisan::output();

    //     return response()->json([
    //         'exit_code' => $exitCode,
    //         'output' => $output,
    //     ]);
    // }

    public function logout(){
        Auth::logout();
    }

    public function sendMail()
    {
        return view('send-mail');
    }

    public function doSendMail(MailRequest $request)
    {
        $data = [$request->first_name, $request->last_name, $request->username, $request->email, $request->location, $request->question];
        $email = Mail::to($request->email)->send(new SendFormattedMail(1, $data, ['storage/user/images/Image_2024_10_30_06_21_15.jpg']));
        if ($email) {
            return response()->json(['succes' => 1, 'msg' => 'Message Send to the ' . $request->email . ' address.']);
        }
        return response()->json(['succes' => 1, 'msg' => 'Mail not send, something went wrong.']);
    }
    public function resetPassword()
    {
        return view('reset-password');
    }
    public function doResetPassword(Request $request)
    {
        dd($request->toArray());
    }
    public function changeStatus()
    {
        Log::info('disconnected');
    }
    public function changeStatus2()
    {
        Log::info('Connected');
    }
    public function addQuote(Request $request)
    {
        foreach ($request->quotes as $key => $value) {
            QuoteTable::insert([
                'quote' => $value['quote'] ?? 'No Quote'
            ]);
        }
    }

    public function scroll()
    {
        $data = QuoteTable::orderBy('id', 'DESC')->paginate(20);
        $hasMorePage = $data->hasMorePages();
        $nextPageUrl = $data->nextPageUrl();
        $data = $data->getCollection()->reverse();
        if (request()->ajax()) {
            return response()->json(['success' => 1, 'html' => view('inc.scroll-partial', compact('data'))->render(), 'nextPageUrl' => $hasMorePage ? $nextPageUrl : false]);
        }
        return view('scroll', compact('data', 'hasMorePage', 'nextPageUrl'));
    }

    public function addCreatedAtDates()
    {
        $all = QuoteTable::all();
        $i = 1;
        $date = Carbon::now();
        foreach ($all as $key => $value) {
            if ($i > 4) {
                $i = 0;
                $date = $date->subDays(1);
            }
            $value->created_at = $date->format('Y-m-d H:i:s');
            $value->save();
            $i++;
        }
    }

    public function encDes()
    {
        // $testMsg = TestMessage::find(1);
        // dd($testMsg->toArray());
        $n = new TestMessage();
        $n->content = 'Lorem ipsum doller si.';
        $n->save();
    }

    public function historyManagement($name = '')
    {
        return view('history_management', compact('name'));
    }

    public function count($name)
    {
        $length = strlen($name);
        $uniqueArr = [];
        for ($i = 0; $i < $length; $i++) {
            $char = $name[$i];
            if (!isset($uniqueArr[$char])) {
                $uniqueArr[$char] = $name[$i];
            }
        }
        return $uniqueArr;
    }

    public function rotatingArrayFromKPosition()
    {
        $nums = [1, 2, 3, 4, 5]; 
        $k = -1;
        $numlength = count($nums);
        if($k < 0){
            return response()->json(['The target is less than zero.']);
        }
        if ($numlength < $k) {
            return response()->json(['The array is too short.']);
        }
        $newArray = [];
        for ($j = 1; $j <= $k; $j++) {
            $newArray[] = $nums[$numlength - $j];
        }
        for ($i = 0; $i < $numlength - $k; $i++) {
            $newArray[] = $nums[$i];
        }
        return $newArray;
    }

    public function updateStatus(){
        return view('update_status');
    }

    public function doUpdateStatus(OnlineStatus $request){
        if(auth()->check()){
            $user = auth()->user();
            $user->online_status = $request->online_status;
            $user->save();
        }
    }
}
