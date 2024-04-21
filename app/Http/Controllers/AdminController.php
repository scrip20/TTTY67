<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManagerStatic as Image;

class AdminController extends Controller
{
    private $admin;

    public function __construct(Admin $admin)
    {
        $this->admin = $admin;
    }

    /********** Login Functions **********/
    /**
     * View the login page 
     */
    public function login()
    {
        if (auth()->check()) {
            return  redirect('/stories/1');
        }
        return view('auth.login');
    }

    /**
     * Check from login data   
     */
    public function tryLogin(Request $request)
    {
        // Validate the request data
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        // Check if the admin exists
        $admin = Admin::where('email', $request->email)->where('password', $request->password)->first();

        // If the admin exists, proceed with the login process
        if ($admin) {
            // El usuario NO está bloqueado, proceder según el rol.
            if ($admin->locked === 0) {
                // Get the authenticated user
                $user = auth()->guard('admin')->user();
                if ($user->role === 'admin') {
                    return redirect('/home');
                } else {
                    return redirect('/stories/1');
                }
            } else {
                // El usuario está bloqueado, cerrar sesión y mostrar mensaje de error.
                auth()->guard('admin')->logout();
                return back()->withErrors(["email" => "Lo sentimos, su cuenta ha sido bloqueada. Comuníquese con la administración"]);
            }
        } else {
            return back()->withErrors(["email" => "Correo electrónico o la contraseña son incorrectos"]);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        auth()->logout();
        session()->flush();

        return redirect('/');
    }

    /********** Profile Functions **********/
    /**
     * Show the profile page
     */
    public function profile()
    {
        return view('profile');
    }

    /**
     * Edit name in profile page
     */
    public function editName(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'username' => 'required|string|max:255|min:3',
        ]);

        // Update the admin's name
        $admin = auth()->user();
        $admin->name = $request->username;
        $admin->save();

        // Redirect back to the previous page
        // return back();
        // return the admin name save
        return response()->json(['username' => $request->username]);
    }

    /**
     * Edit profile photo in profile page
     */
    public function editProfilePhoto(Request $request)
    {
        // Validate the uploaded file
        $request->validate(
            [
                'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            ]
        );
        // image processing
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            if ($image->isValid()) {
                // get admin data
                $admin = auth()->user();

                // Delete the old image if it's not the default one
                if ($admin->image !== 'profile.svg') {

                    $oldImagePath = 'upload/profiles_photos/' . $admin->image;
                    $oldThumbnailPath = 'upload/profiles_photos/thumbs/' . $admin->image;

                    if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                        Storage::disk('public')->delete($oldImagePath);
                    }

                    if ($oldThumbnailPath && Storage::disk('public')->exists($oldThumbnailPath)) {
                        Storage::disk('public')->delete($oldThumbnailPath);
                    }
                }

                // create image name
                $imageName = "admin_" . auth()->user()->id . "_" . now()->timestamp . '.' . $image->getClientOriginalExtension();
                // store image
                $image->storeAs('upload/profiles_photos/', $imageName, ['disk' => 'public']);

                // Generate thumbnail
                $thumbnailPath = '/storage/upload/profiles_photos/thumbs/' . $imageName;
                Image::make($image)->fit(200,200)->save(public_path($thumbnailPath));

                // Update the image in the database
                $admin->image = $imageName;
                $admin->save();

                // Update the URL of the image to return it to the view
                $imageUrl = asset('storage/upload/profiles_photos/' . $imageName);
                $thumbUrl = asset('storage/upload/profiles_photos/thumbs/' . $imageName);


                // Return the updated image URLs
                return response()->json([
                    'url' => $imageUrl,
                    'thumbUrl' => $thumbUrl,
                ]);
            } else {
                // Invalid image file
                return response()->json(['image' => ''],422);
            }
        }
    }

    /**
     * Change password in profile page
     */
    public function changePassword(Request $request)
    {
        // Validate the request data
        $validated = $request->validate(
            [
                'old_password' => 'required|string|min:8',
                'new_password' => 'required|string|min:8|confirmed',
                'new_password_confirmation' => 'required|string|min:8',
            ]
        );

        // Check if the old password matches the authenticated admin's password
        if (!Hash::check($request->old_password, auth()->user()->password)) {
            return response()->json(['message' => "La contraseña anterior es incorrecta"],401);
        }

        // Update the admin's password
        Admin::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json(['success' => 'La contraseña ha sido cambiada con éxito.']);
    }

    /********** Management Functions **********/
    /**
     * Show all manager except admin
     */
    public function show(Request $request)
    {
        // check from the search parameter
        $search = $request->query('search');

        // Construct the query to retrieve managers
        $query = $this->admin::query()->where('role', 'manager')->when($search, function ($query, $search) {
            // get only the name that match the search
            return $query->where('name', 'like', '%' . $search . '%');
        });

        // if the request from search action
        if ($request->ajax()) {
            // Fetch a maximum of8 manager names for Ajax response
            return $query->take(8)->pluck('name');
        }

        // Fetch all managers for non-Ajax request
        $admins = $query->get();

        // Pass managers and search query to the view
        return view('admin', compact('admins', 'search'));
    }

    /**
     * Store new manager
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate(
            [
                'username' => 'required|min:3',
                'email' => 'required|email|unique:admins',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
            ]
        );

        $newAdmin = new Admin();
        $newAdmin->name = $request->username;
        $newAdmin->image = 'profile.svg';
        $newAdmin->email = $request->email;
        $newAdmin->password = Hash::make($request->password);
        $newAdmin->role = 'manager';
        $newAdmin->locked =0;

        $newAdmin->save();

        // Redirect back to the previous page
        return back();
    }

    /**
     * Update the specified manager
     */
    public function update(Request $request)
    {
        // validate from data in the request
        $validatedData = $request->validate(
            [
                'username' => 'required|string|max:255|min:3',
                'email' => [
                    'required', 'string', 'email', 'max:255',
                    Rule::unique('admins')->ignore($request->input('edit_admin_id')),
                ],
            ]
        );

        // get the admin form database
        $admin = Admin::find($request->edit_admin_id);

        // Update the admin data
        $admin->name = $request->username;
        $admin->email = $request->email;

        // Update the password if it's provided
        if (!empty($request->password)) {
            $validatedData = $request->validate(
                [
                    'password' => 'required|string|min:8|confirmed',
                    'password_confirmation' => 'required',
                ]
            );
        }

        // Save the changes to the admin object
        $admin->save();

        // Redirect back to the previous page
        return back();
    }

    /**
     * Change manager status
     */
    public function adminChangeLocked(Request $request)
    {
        // Find the admin by ID
        $admin = Admin::findOrFail($request->admin_id);

        // Update the locked status
        $admin->locked = $request->locked;
        $admin->save();

        // Return a JSON response indicating success
        return response()->json(['success' => 'Status changed successfully.']);
    }

    /**
     * Remove the specified manager
     */
    public function destroy(Request $request)
    {

        $admin = Admin::findOrFail($request->admin_id);
        // Get the photo filename
        $image = $admin->image;

        // Delete the admin's photo if it is not the default one
        if ($image !== 'profile.svg') {
            // Define the file paths for the photo and its thumbnail
            $imagePath = 'upload/profiles_photos/' . $image;
            $thumbPath = 'upload/profiles_photos/thumbs/' . $image;

            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }

            if ($thumbPath && Storage::disk('public')->exists($thumbPath)) {
                Storage::disk('public')->delete($thumbPath);
            }
        }

        // Delete the admin record
        $admin->delete();

        // Redirect back to the previous page
        return back();
    }
}
