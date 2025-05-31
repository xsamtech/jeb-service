<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Cart as ResourcesCart;
use App\Http\Resources\Expense as ResourcesExpense;
use App\Http\Resources\Panel as ResourcesPanel;
use App\Http\Resources\Role as ResourcesRole;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Accountancy;
use App\Models\Cart;
use App\Models\Expense;
use App\Models\File;
use App\Models\Panel;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

/**
 * @author Xanders
 * @see https://team.xsamtech.com/xanderssamoth
 */
class DashboardController extends Controller
{
    // ==================================== HTTP GET METHODS ====================================
    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }

    /**
     * GET: Panels list page
     *
     * @return \Illuminate\View\View
     */
    public function panels()
    {
        return view('panels');
    }

    /**
     * GET: Expenses list page
     *
     * @return \Illuminate\View\View
     */
    public function expenses()
    {
        return view('expenses');
    }

    /**
     * GET: Users list page
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        return view('users');
    }

    /**
     * GET: Users list page
     *
     * @param  string $entity
     * @return \Illuminate\View\View
     */
    public function usersEntity($entity)
    {
        if (!in_array($entity, ['roles', 'orders'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        return view('users');
    }

    /**
     * GET: Show panel page
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function panelDatas($id)
    {
        $panel = Panel::find($id);

        if (!$panel) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Panneau non trouvé.');
        }

        return view('panels', ['selected_panel' => new ResourcesPanel($panel)]);
    }

    /**
     * GET: Show expense page
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function expenseDatas($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Dépense non trouvée.');
        }

        return view('expenses', ['selected_expense' => new ResourcesExpense($expense)]);
    }

    /**
     * GET: Show user page
     *
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function userDatas($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Utilisateur non trouvé.');
        }

        return view('users', ['selected_user' => new ResourcesUser($user)]);
    }

    /**
     * GET: Show user page
     *
     * @param  string $entity
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function userEntityDatas($entity, $id)
    {
        if (!in_array($entity, ['roles', 'orders'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        if ($entity == 'roles') {
            $role = Role::find($id);

            if (!$role) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', 'Rôle non trouvé.');
            }

            return view('users', ['selected_role' => new ResourcesRole($role)]);
        }

        if ($entity == 'orders') {
            $cart = Cart::find($id);

            if (!$cart) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', 'Commandes non trouvées.');
            }

            return view('users', ['selected_cart' => new ResourcesCart($cart)]);
        }
    }

    /**
     * GET: Statistics page
     *
     * @return \Illuminate\View\View
     */
    public function statistics()
    {
        return view('statistics');
    }

    /**
     * GET: account page
     *
     * @return \Illuminate\View\View
     */
    public function account()
    {
        return view('account');
    }

    // ==================================== HTTP DELETE METHODS ====================================
    /**
     * GET: Delete panel
     *
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removePanel($id)
    {
        $panel = Panel::find($id);

        if (!$panel) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Panneau non trouvé.');
        }

        $filesToDelete = File::where('panel_id', $panel->id)->get();

        foreach ($filesToDelete as $file) {
            // Delete the file from the file system
            $relativeStoragePath = str_replace(getWebURL() . '/storage/', '', $file->file_url);

            Storage::disk('public')->delete($relativeStoragePath);

            // Deletes the row at the database
            $file->delete();
        }

        $panel->delete();

        return redirect('/panels')->with('success_message', 'Panneau supprimé.');
    }

    /**
     * GET: Delete expense
     *
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeExpense($id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Dépense non trouvée.');
        }

        $accountancy = Accountancy::where('expense_id', $expense->id)->first();

        // Wihdraw panel & accountancy order
        $accountancy->delete();
        $expense->delete();

        return redirect('/expenses')->with('success_message', 'Dépense supprimée.');
    }

    /**
     * GET: Delete customer
     *
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeUser($id)
    {
        $user = User::find($id);

        if (!$user) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Utilisateur non trouvé.');
        }

        $user->delete();

        return redirect('/users')->with('success_message', 'Utilisateur supprimé.');
    }

    /**
     * GET: Delete customer
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $entity
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeUserEntity(Request $request, $entity, $id)
    {
        if (!in_array($entity, ['roles', 'orders', 'cart'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        if ($entity == 'roles') {
            $role = Role::find($id);

            if (!$role) {
                return back()->with('error_message', 'Rôle non trouvé.');
            }

            $role->delete();

            return redirect('/users/' . $entity)->with('success_message', 'Rôle supprimé.');
        }

        if ($entity == 'orders') {
            // Check if cart exists, is unpaid, and contains the requested panel
            $cart = Cart::where([['id', $request->cart_id], ['is_paid', 0]])
                            ->whereHas('panels', function ($query) use ($id) {
                                $query->where('panels.id', $id);
                            })->first();

            if (!$cart) {
                return back()->with('error_message', 'Commande de panneau non trouvée.');
            }

            // Get the ordered panel with the pivot relationship
            $ordered_panel = $cart->panels()->where('panels.id', $id)->first();

            if (!$ordered_panel || !$ordered_panel->pivot) {
                return back()->with('error_message', 'Panneau non trouvé dans la commande.');
            }

            $quantity = (int) $ordered_panel->pivot->quantity;

            if ($quantity <= 0) {
                return back()->with('error_message', 'Quantité invalide détectée.');
            }

            // Retrieve the panel in stock
            $in_stock_panel = Panel::find($id);

            if (!$in_stock_panel) {
                return back()->with('error_message', 'Panneau introuvable dans le stock.');
            }

            // Updates the panel stock
            $in_stock_panel->update([
                'quantity' => $in_stock_panel->quantity + $quantity
            ]);

            // Remove the panel from cart
            $cart->panels()->detach($id);

            return redirect('/users/' . $entity . '/' . $cart->id)->with('success_message', 'Panneau retiré de la commande.');
        }

        if ($entity == 'cart') {
            // Check the existence of the unpaid cart containing the panel order
            $cart = Cart::find('id');

            if (!$cart) {
                return back()->with('error_message', 'Commandes non trouvées.');
            }

            $accountancy = Accountancy::where('cart_id', $cart->id)->first();

            // Wihdraw panel & accountancy order
            $accountancy->delete();
            $cart->delete();

            return redirect('/users/orders')->with('success_message', 'Commandes supprimée.');
        }
    }

    // ==================================== HTTP POST METHODS ====================================
    /**
     * POST: Update account
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateAccount(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        // Preparing dynamic rules
        $rules = [];

        if ($request->has('firstname')) {
            $rules['firstname'] = ['required', 'string', 'max:255'];
        }

        if ($request->has('lastname')) {
            $rules['lastname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('surname')) {
            $rules['surname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('gender')) {
            $rules['gender'] = ['nullable', Rule::in(['M', 'F'])];
        }

        if ($request->has('birthdate')) {
            $rules['birthdate'] = ['nullable', 'date_format:d/m/Y'];
        }

        if ($request->has('p_o_box')) {
            $rules['p_o_box'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('address_1')) {
            $rules['address_1'] = ['nullable', 'string'];
        }

        if ($request->has('address_2')) {
            $rules['address_2'] = ['nullable', 'string'];
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->has('username') && $request->input('username') !== $user->username) {
            $rules['username'] = ['required', 'string', 'max:45', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        if ($request->has('image_64')) {
            $rules['image_64'] = ['required', 'string', 'starts_with:data:image/'];
        }

        // Validation of present fields only
        $validated = $request->validate($rules);

        // Date formatting
        if (isset($validated['birthdate'])) {
            $validated['birthdate'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Password hash if present
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Processing of the base64 image if present
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_url = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_url, base64_decode($image));
            $validated['avatar_url'] = $image_url;

            unset($validated['image_64']);
        }

        // Update user with valid fields
        $user->update($validated);

        // Update PasswordReset only if necessary
        $password_reset = !empty($user->email)
            ? \App\Models\PasswordReset::where('email', $user->email)->first()
            : \App\Models\PasswordReset::where('phone', $user->phone)->first();

        if ($password_reset) {
            $updateData = [];

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
            }

            $updateData['token'] = (string) random_int(1000000, 9999999);

            $password_reset->update($updateData);
        }

        // Conditional return: AJAX or HTML POST
        return $request->expectsJson()
            ? response()->json(['success_message' => true, 'avatar_url' => $user->avatar_url ?? null])
            : back()->with('success_message', 'Vos informations ont bien été mises à jour.');
    }

    /**
     * POST: Add a panel
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addPanel(Request $request)
    {
        $request->validate([
            'dimensions' => ['nullable', 'string', 'max:255'],
            'format' => ['nullable', 'string'],
            'unit_price' => ['nullable', 'numeric', 'between:0,9999999.99'],
            'location' => ['nullable', 'string'],
            'quantity' => ['nullable', 'integer', 'min:0'],
            'is_available' => ['required', 'boolean'],
            'images_urls.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp,gif', 'max:2048'],
            'file_name' => ['nullable', 'string'],
        ]);

        $panel = Panel::create([
            'dimensions' => $request->dimensions,
            'format' => $request->format,
            'unit_price' => $request->unit_price,
            'location' => $request->location,
            'quantity' => $request->quantity,
            'is_available' => $request->is_available,
            'created_by' => Auth::id(),
        ]);

        // If image files exist
        if ($request->hasFile('images_urls')) {
            foreach ($request->file('images_urls') as $singleFile) {
                $extension = $singleFile->getClientOriginalExtension();
                $uniqueName = Str::random(50) . '.' . $extension;
                $relativePath = 'images/messages/' . $panel->id . '/' . $uniqueName;

                // Storage in the public disk
                $singleFile->storeAs('images/messages/' . $panel->id, $uniqueName, 'public');

                File::create([
                    'file_name' => trim($request->file_name ?? '') ?: $singleFile->getClientOriginalName(),
                    'file_url' => getWebURL() . '/storage/' . $relativePath,
                    'file_type' => 'photo',
                    'panel_id' => $panel->id,
                ]);
            }
        }

        return back()->with('success_message', 'Panneau ajouté.');
    }

    /**
     * POST: Add a expense
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addExpense(Request $request)
    {
        $request->validate([
            'designation' => ['required', 'string', 'max:255'],
            'amount' => ['nullable', 'numeric', 'between:0,9999999.99'],
            'outflow_date' => ['nullable', 'string']
        ]);

        $outflow = null;

        if ($request->filled('outflow_date')) {
            $parts = explode(' ', $request->outflow_date); // ['30/05/2025', '14:30']

            if (count($parts) === 2) {
                [$day, $month, $year] = explode('/', $parts[0]);
                $time = $parts[1];
                $outflow = "$year-$month-$day $time:00"; // DATETIME format
            }
        }

        $expense = Expense::create([
            'designation' => $request->designation,
            'amount' => $request->amount,
            'outflow_date' => $outflow,
            'created_by' => Auth::id(),
        ]);

        Accountancy::create([
            'expense_id' => $expense->id
        ]);

        return back()->with('success_message', 'Dépense ajoutée.');
    }

    /**
     * POST: Add a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addUser(Request $request)
    {
        $random_int_stringified = (string) random_int(1000000, 9999999);

        // Validate fields
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['string', 'username', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'firstname.required' => 'Le prénom est requis.',
            'email.required' => 'L\'email est requis.',
            'email.email' => 'Le format de l\'email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'password.required' => 'Le mot de passe est requis.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Register user
        $user = User::create([
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'surname' => $request->surname,
            'gender' => $request->gender,
            'birthdate' => isset($request->birthdate) ? explode('/', $request->birthdate)[2] . '-' . explode('/', $request->birthdate)[1] . '-' . explode('/', $request->birthdate)[0] : null,
            'address_1' => $request->address_1,
            'address_2' => $request->address_2,
            'p_o_box' => $request->p_o_box,
            'email' => $request->email,
            'phone' => $request->phone,
            'username' => $request->username,
            'password' => Hash::make($request->password),
        ]);

        // Register password reset data
        PasswordReset::create([
            'email' => $request->email,
            'phone' => $request->phone,
            'token' => $random_int_stringified,
            'former_password' => $request->password
        ]);

        if (isset($request->image_64)) {
            // $extension = explode('/', explode(':', substr($request->image_64, 0, strpos($request->image_64, ';')))[1])[1];
            $replace = substr($request->image_64, 0, strpos($request->image_64, ',') + 1);
            // Find substring from replace here eg: data:image/png;base64,
            $image = str_replace($replace, '', $request->image_64);
            $image = str_replace(' ', '+', $image);
            // Create image URL
            $image_url = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            // Upload image
            Storage::url(Storage::disk('public')->put($image_url, base64_decode($image)));

            $user->update([
                'avatar_url' => $image_url,
                'updated_at' => now()
            ]);
        }

        // Register user with role
        $user->roles()->attach([$request->role_id]);

        // The API token
        $token = $user->createToken('auth_token')->plainTextToken;

        $user->update([
            'api_token' => $token,
            'updated_at' => now()
        ]);

        return back()->with('success_message', 'Utilisateur ajouté.');
    }

    /**
     * POST: Add a user entity
     *
     * @param  string $entity
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function addUserEntity(Request $request, $entity)
    {
        if (!in_array($entity, ['roles', 'orders'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        if ($entity == 'roles') {
            // Validate fields
            $request->validate([
                'role_name' => ['required', 'string', 'max:255'],
            ], [
                'role_name.required' => 'Le nom du rôle est requis.'
            ]);

            // Register role
            Role::create([
                'role_name' => $request->role_name,
                'role_description' => $request->role_description,
                'created_by' => Auth::user()->id,
            ]);
        }

        if ($entity == 'orders') {
            DB::beginTransaction();

            try {
                $customer = User::find($request->customer_id);

                if (!$customer) {
                    return back()->with('error_message', 'Utilisateur client non trouvé.');
                }

                // Cart creation
                $cart = Cart::create([
                    'user_id' => $customer->id,
                    'payment_code' => Str::random(10),
                    'is_paid' => 0,
                ]);

                // Retrieving panel IDs and quantities
                $panels_ids = $request->panels_ids;
                $quantities = $request->quantities;

                foreach ($panels_ids as $index => $panel_id) {
                    $quantity = $quantities[$index];
                    $panel = Panel::find($panel_id);

                    if (!$panel || !$panel->is_available) {
                        continue;
                    }

                    // Check if the requested quantity is available
                    if ($panel->quantity < $quantity) {
                        return back()->with('error_message', 'Quantité insuffisante pour le panneau de dimension « ' . $panel->dimensions . ' » et de format « ' . $panel->format . ' »<br>');
                    }

                    // Attach to cart with quantity
                    $cart->panels()->attach($panel_id, ['quantity' => $quantity, 'is_valid' => 1]);

                    // Decrement panel stock
                    $panel->quantity -= $quantity;

                    // If the quantity reaches zero, make the panel unavailable
                    if ($panel->quantity <= 0) {
                        $panel->is_available = 0;
                    }

                    $panel->save(); // Save changes
                }

                // Accountancy data added
                Accountancy::create(['cart_id' => $cart->id]);
                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('error_message', $e . '<br>Erreur lors de la création du panier.');
            }
        }

        return back()->with('success_message', 'Utilisateur ajouté.');
    }

    /**
     * POST: Update some panel
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updatePanel(Request $request, $id)
    {
        $panel = Panel::find($id);

        if (!$panel) {
            return back()->with('error_message', 'Panneau non trouvé.');
        }

        // Dynamic validation
        $rules = [];

        if ($request->has('dimensions')) {
            $rules['dimensions'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('format')) {
            $rules['format'] = ['nullable', 'string', 'max:65535'];
        }

        if ($request->has('unit_price')) {
            $rules['unit_price'] = ['nullable', 'numeric', 'between:0,9999999.99'];
        }

        if ($request->has('location')) {
            $rules['location'] = ['nullable', 'string', 'max:65535'];
        }

        if ($request->has('quantity')) {
            $rules['quantity'] = ['nullable', 'integer', 'min:0'];
        }

        if ($request->has('is_available')) {
            $rules['is_available'] = ['required', 'boolean'];
        }

        if ($request->hasFile('images_urls')) {
            $rules['images_urls.*'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp,gif', 'max:2048'];
            $rules['file_name'] = ['nullable', 'string'];
        }

        if ($request->has('deleted_file_ids')) {
            $rules['deleted_file_ids'] = ['array'];
            $rules['deleted_file_ids.*'] = ['integer', 'exists:files,id'];
        }

        $validated = $request->validate($rules);

        $validated['updated_by'] = Auth::id();

        // Fields update
        $panel->update($validated);

        // Deleting selected files
        if (!empty($request->deleted_file_ids)) {
            $filesToDelete = File::whereIn('id', $request->deleted_file_ids)->get();

            foreach ($filesToDelete as $file) {
                // Delete the file from the file system (if you want)
                $relativeStoragePath = str_replace(getWebURL() . '/storage/', '', $file->file_url);

                Storage::disk('public')->delete($relativeStoragePath);

                // Deletes the row at the database
                $file->delete();
            }
        }

        // Adding new files if any
        if ($request->hasFile('images_urls')) {
            foreach ($request->file('images_urls') as $singleFile) {
                $extension = $singleFile->getClientOriginalExtension();
                $uniqueName = Str::random(50) . '.' . $extension;
                $relativePath = 'images/messages/' . $panel->id . '/' . $uniqueName;

                $singleFile->storeAs('images/messages/' . $panel->id, $uniqueName, 'public');

                File::create([
                    'file_name' => trim($request->file_name ?? '') ?: $singleFile->getClientOriginalName(),
                    'file_url' => getWebURL() . '/storage/' . $relativePath,
                    'file_type' => 'photo',
                    'panel_id' => $panel->id,
                ]);
            }
        }

        return back()->with('success_message', 'Panneau mis à jour.');
    }

    /**
     * POST: Increment/Decrement quantity in the panel/cart
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $entity
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updatePanelQuantity(Request $request, $entity, $id)
    {
        $request->validate([
            'amount' => ['nullable', 'integer', 'min:1'],
            'operation' => ['required', 'in:inc,dec'],
            'cart_id' => ['required_if:entity,ordered_panel', 'integer']
        ]);

        $amount = $request->input('amount', 1);
        $operation = $request->input('operation');

        /** In-stock panel management **/
        if ($entity === 'stock_panel') {
            $panel = Panel::find($id);

            if (!$panel) {
                return response()->json(['error' => 'Panneau non trouvé.'], 404);
            }

            if ($operation === 'inc') {
                $panel->increment('quantity', $amount);

            } elseif ($operation === 'dec') {
                if ($panel->quantity < $amount) {
                    return response()->json(['error' => 'Quantité en stock insuffisante.'], 400);
                }

                $panel->decrement('quantity', $amount);
            }

            return response()->json([
                'message' => "Stock mis à jour.",
                'quantity' => $panel->quantity
            ]);
        }

        /** Managing an order (cart_panel) **/
        if ($entity === 'ordered_panel') {
            $cart = Cart::with(['panels' => function ($query) use ($id) {
                                $query->where('panels.id', $id);
                            }])->find($request->cart_id);

            if (!$cart) {
                return response()->json(['error' => 'Panier non trouvé.'], 404);
            }

            $panel = $cart->panels->first();

            if (!$panel) {
                return response()->json(['error' => 'Panneau non présent dans la commande.'], 404);
            }

            $pivot = $panel->pivot;
            $orderedQty = $pivot->quantity;
            $stockQty = $panel->quantity;

            if ($operation === 'inc') {
                if ($stockQty < $amount) {
                    return response()->json([
                        'error' => "Stock insuffisant. Il reste $stockQty unité(s).",
                    ], 400);
                }

                $cart->panels()->updateExistingPivot($panel->id, [
                    'quantity' => $orderedQty + $amount,
                    'updated_at' => now(),
                ]);
                $panel->decrement('quantity', $amount);

                return response()->json([
                    'message' => "Quantité commandée augmentée.",
                    'new_ordered_quantity' => $orderedQty + $amount,
                    'remaining_stock' => $panel->fresh()->quantity,
                ]);
            }

            if ($operation === 'dec') {
                if ($orderedQty <= $amount) {
                    $panel->increment('quantity', $orderedQty);
                    $cart->panels()->detach($panel->id);

                    return response()->json([
                        'message' => "Commande annulée.",
                        'new_ordered_quantity' => 0,
                        'remaining_stock' => $panel->fresh()->quantity,
                    ]);
                }

                $cart->panels()->updateExistingPivot($panel->id, [
                    'quantity' => $orderedQty - $amount,
                    'updated_at' => now(),
                ]);
                $panel->increment('quantity', $amount);

                return response()->json([
                    'message' => "Quantité commandée réduite.",
                    'new_ordered_quantity' => $orderedQty - $amount,
                    'remaining_stock' => $panel->fresh()->quantity,
                ]);
            }
        }

        return response()->json(['error' => 'Entité inconnue.'], 400);
    }

    /**
     * POST: Update some user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateExpense(Request $request, $id)
    {
        $expense = Expense::find($id);

        if (!$expense) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Dépense non trouvée.');
        }

        // Preparing dynamic rules
        $rules = [];

        if ($request->has('designation')) {
            $rules['designation'] = ['required', 'string', 'max:255'];
        }

        if ($request->has('amount')) {
            $rules['amount'] = ['nullable', 'numeric', 'between:0,9999999.99'];
        }

        // Validation of present fields only
        $validated = $request->validate($rules);

        if ($request->filled('outflow_date')) {
            $parts = explode(' ', $request->outflow_date); // ['30/05/2025', '14:30']

            if (count($parts) === 2) {
                [$day, $month, $year] = explode('/', $parts[0]);
                $time = $parts[1];
                $validated['outflow_date'] = "$year-$month-$day $time:00"; // DATETIME format
            }
        }

        // Update expense with valid fields
        $expense->update($validated);

        return back()->with('success_message', 'Dépense mise à jour.');
    }

    /**
     * POST: Update some user
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error_message', 'Utilisateur non trouvé.');
        }

        // Preparing dynamic rules
        $rules = [];

        if ($request->has('firstname')) {
            $rules['firstname'] = ['required', 'string', 'max:255'];
        }

        if ($request->has('lastname')) {
            $rules['lastname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('surname')) {
            $rules['surname'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('gender')) {
            $rules['gender'] = ['nullable', Rule::in(['M', 'F'])];
        }

        if ($request->has('birthdate')) {
            $rules['birthdate'] = ['nullable', 'date_format:d/m/Y'];
        }

        if ($request->has('p_o_box')) {
            $rules['p_o_box'] = ['nullable', 'string', 'max:255'];
        }

        if ($request->has('address_1')) {
            $rules['address_1'] = ['nullable', 'string'];
        }

        if ($request->has('address_2')) {
            $rules['address_2'] = ['nullable', 'string'];
        }

        if ($request->has('phone')) {
            $rules['phone'] = ['nullable', 'string', 'max:20'];
        }

        if ($request->has('email') && $request->input('email') !== $user->email) {
            $rules['email'] = ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->has('username') && $request->input('username') !== $user->username) {
            $rules['username'] = ['required', 'string', 'max:45', Rule::unique('users')->ignore($user->id)];
        }

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        if ($request->has('image_64')) {
            $rules['image_64'] = ['required', 'string', 'starts_with:data:image/'];
        }

        // Validation of present fields only
        $validated = $request->validate($rules);

        // Date formatting
        if (isset($validated['birthdate'])) {
            $validated['birthdate'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Password hash if present
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        // Processing of the base64 image if present
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_url = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_url, base64_decode($image));
            $validated['avatar_url'] = $image_url;

            unset($validated['image_64']);
        }

        // Update user with valid fields
        $user->update($validated);

        // Update PasswordReset only if necessary
        $password_reset = !empty($user->email)
            ? \App\Models\PasswordReset::where('email', $user->email)->first()
            : \App\Models\PasswordReset::where('phone', $user->phone)->first();

        if ($password_reset) {
            $updateData = [];

            if ($request->filled('email')) {
                $updateData['email'] = $request->email;
            }

            if ($request->filled('phone')) {
                $updateData['phone'] = $request->phone;
            }

            $updateData['token'] = (string) random_int(1000000, 9999999);

            $password_reset->update($updateData);
        }

        if ($request->filled('role_id')) {
            $user->roles()->syncWithoutDetaching([$request->role_id]);
        }

        return back()->with('success_message', 'Vos informations ont bien été mises à jour.');
    }

    /**
     * POST: Update user entity
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $entity
     * @param  int  $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function updateUserEntity(Request $request, $entity, $id)
    {
        if (!in_array($entity, ['roles', 'orders', 'cart'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        if ($entity == 'roles') {
            $role = Role::find($id);

            if (!$role) {
                return back()->with('error_message', 'Rôle non trouvé.');
            }

            // Preparing dynamic rules
            $rules = [];

            if ($request->has('role_name')) {
                $rules['role_name'] = ['required', 'string', 'max:255'];
            }

            if ($request->has('role_description')) {
                $rules['role_description'] = ['nullable', 'string', 'max:255'];
            }

            // Validation of present fields only
            $validated = $request->validate($rules);

            $validated['updated_by'] = Auth::user()->id;

            // Update role with valid fields
            $role->update($validated);

            return back()->with('success_message', 'Vos informations ont bien été mises à jour.');
        }

        if ($entity == 'orders') {
            $cart = Cart::with('panels')->find($id);

            if (!$cart) {
                return back()->with('error_message', 'Panier non trouvé.');
            }

            $panels_ids = $request->panels_ids;
            $quantities = $request->quantities;

            DB::beginTransaction();

            try {
                foreach ($panels_ids as $index => $panel_id) {
                    $newQuantity = (int) $quantities[$index];
                    $panel = Panel::find($panel_id);

                    if (!$panel || !$panel->is_available) {
                        continue;
                    }

                    $pivot = $cart->panels->firstWhere('id', $panel_id);
                    $oldQuantity = $pivot ? (int) $pivot->pivot->quantity : 0;
                    $delta = $newQuantity - $oldQuantity;

                    // If we request more than the available stock
                    if ($delta > 0 && $panel->quantity < $delta) {
                        return back()->with('error_message', "Stock insuffisant pour le panneau {$panel->dimensions}. Disponible : {$panel->quantity}, demandé en plus : {$delta}");
                    }

                    // Stock adjustment
                    $panel->quantity -= $delta;

                    // Availability update
                    if ($panel->quantity <= 0) {
                        $panel->is_available = 0;
                        $panel->quantity = 0;
                    }

                    $panel->save();

                    // Update or add in the pivot
                    $cart->panels()->syncWithoutDetaching([
                        $panel_id => ['quantity' => $newQuantity, 'is_valid' => 1],
                    ]);
                }

                DB::commit();

                return back()->with('success_message', 'Panneaux mis à jour dans le panier.');

            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('error_message', 'Erreur lors de la mise à jour : ' . $e->getMessage());
            }
        }

        if ($entity == 'cart') {
            $cart = Cart::find($id);

            if (!$cart) {
                return back()->with('error_message', 'Panier non trouvé.');
            }

            $updates = [];
            $message = 'Mise à jour terminée.';

            if ($request->filled('is_paid')) {
                $updates['is_paid'] = $request->is_paid;

                if ($request->is_paid == 1) {
                    $updates['payment_code'] = (string) random_int(1000000, 9999999);
                    $message = 'Commandes payées.';

                } else {
                    $updates['payment_code'] = null;
                }
            }

            if ($request->filled('user_id')) {
                $updates['user_id'] = $request->user_id;
                $message = 'Mise à jour terminée.';
            }

            if (!empty($updates)) {
                $cart->update($updates);

                return back()->with('success_message', $message);
            }

            // No fields to update
            return back()->with('error_message', 'Aucune donnée à mettre à jour.');
        }
    }
}
