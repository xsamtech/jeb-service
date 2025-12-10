<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\Expense as ResourcesExpense;
use App\Http\Resources\Panel as ResourcesPanel;
use App\Http\Resources\Role as ResourcesRole;
use App\Http\Resources\User as ResourcesUser;
use App\Models\Expense;
use App\Models\Face;
use App\Models\File;
use App\Models\MonthData;
use App\Models\Panel;
use App\Models\PasswordReset;
use App\Models\Role;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Log;
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $monthsInFrench = [
            1 => 'janvier',
            2 => 'février',
            3 => 'mars',
            4 => 'avril',
            5 => 'mai',
            6 => 'juin',
            7 => 'juillet',
            8 => 'août',
            9 => 'septembre',
            10 => 'octobre',
            11 => 'novembre',
            12 => 'décembre',
        ];

        // role "Administrateur"
        $admin_role = Role::where('role_name', 'Administrateur')->first();
        // Récupérer le mois et l'année depuis le formulaire ou utiliser les valeurs par défaut
        $month = $request->get('month', Carbon::now()->month);
        $year = $request->get('year', Carbon::now()->year);
        // Récupérer le nom du mois en fonction de la valeur de $month
        $monthName = $monthsInFrench[$month];

        // Récupérer tous les panneaux avec leurs relations nécessaires
        $panels = Panel::with([
            'expenses' => function ($query) use ($month, $year) {
                $query->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->where('designation', 'Taxe implantation');
            }
        ])->get();

        // Traitement des données pour l'affichage dans la vue
        $panelsData = $panels->map(function ($panel) {
            $taxeImplantation = $panel->expenses->where('designation', 'Taxe implantation')->first();
            $taxeImplantationAmount = $taxeImplantation ? $taxeImplantation->amount : 0;

            return [
                'id' => $panel->id,
                'panel' => $panel->location,
                'taxe_implantation' => $taxeImplantation ? $taxeImplantation->amount : 0,
                'expenses' => $panel->faces->map(function ($face) use ($taxeImplantationAmount) {

                    $rentedFace = $face->rented_faces->sortByDesc('created_at')->first();

                    if (!$rentedFace) {
                        return [
                            'rented_face_id' => null,
                            'face_id' => $face->id,
                            'face_name' => $face->face_name,
                            'face_price' => null,
                            'taxe_affichage' => 0,
                            'date_limite_location' => null,
                            'is_available' => $face->is_available,
                            'other_expenses' => [],
                            'total_other_expenses' => 0,
                            'remaining_amount' => 0,
                        ];
                    }

                    $taxeAffichage = $rentedFace->expenses->where('designation', 'Taxe affichage')->first();
                    $otherExpenses = $rentedFace->expenses->whereNotIn('designation', ['Taxe implantation', 'Taxe affichage']);

                    $taxeAffichageAmount = $taxeAffichage ? $taxeAffichage->amount : 0;
                    $totalOtherExpenses = $otherExpenses->sum('amount');

                    $price = $rentedFace->price;
                    $totalTaxes = $taxeAffichageAmount + $taxeImplantationAmount;
                    $remainingAmount = $price - ($totalTaxes + $totalOtherExpenses);

                    return [
                        'rented_face_id' => $rentedFace->id,
                        'face_id' => $face->id,
                        'face_name' => $face->face_name,
                        'face_price' => $price,
                        'taxe_affichage' => $taxeAffichageAmount,
                        'date_limite_location' => $rentedFace->end_date,
                        'is_available' => $face->is_available,
                        'other_expenses' => $otherExpenses->map(function ($e) {
                            return [
                                'designation' => $e->designation,
                                'amount' => $e->amount,
                            ];
                        }),
                        'total_other_expenses' => $totalOtherExpenses,
                        'remaining_amount' => $remainingAmount,
                    ];
                }),
            ];
        });

        // Total des restes à la caisse pour le mois
        // $totalRemaining = $panelsData->flatMap(fn($panel) => $panel['expenses'])->sum('remaining_amount');
        $monthData = MonthData::where('month', $month)->where('year', $year)->first();
        $monthDataID = $monthData ? $monthData->id : null;
        $totalRemaining = $monthData ? $monthData->remaining_amount : 0;
        $tithePaid = $monthData ? $monthData->tithe_paid : 0;
        // Dîme
        $tithe = $totalRemaining > 0 ? ($totalRemaining / 10) : 0;
        // Month expenses
        $monthExpenses = $monthData ? $monthData->expenses : [];

        // Envoi des données à la vue sans encapsuler dans un JSON
        return view('home', [
            'admin' => $admin_role,
            'panelsData' => $panelsData,
            'month' => $month,
            'year' => $year,
            'monthName' => $monthName,
            'monthDataID' => $monthDataID,
            'totalRemaining' => $totalRemaining,
            'tithe' => $tithe,
            'tithePaid' => $tithePaid,
            'monthExpenses' => $monthExpenses,
        ]);
    }

    /**
     * GET: Datas from sheet page
     *
     * @param  string $entity
     * @param  int $id
     * @return \Illuminate\View\View
     */
    public function datas($entity, $id)
    {
        $entity_title = null;
        $selected_item = null;

        if ($entity == 'panel') {
            $entity_title = 'Détails sur le panneau';
            $selected_item = Panel::find($id);

            if (!$selected_item) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', 'Panneau non trouvé.');
            }
        }

        if ($entity == 'face') {
            $entity_title = 'Détails sur la face';
            $selected_item = Face::find($id);

            if (!$selected_item) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', 'Face non trouvée.');
            }
        }

        return view('home', [
            'selected_item' => $selected_item,
            'entity' => $entity,
            'entity_title' => $entity_title,
        ]);
    }

    /**
     * GET: Home page
     *
     * @return \Illuminate\View\View
     */
    public function test()
    {
        $customer_role = Role::where('role_name', 'Client')->first();
        $unpaid_customers_collection = User::whereHas('roles', function ($query) use ($customer_role) {
            $query->where('roles.id', $customer_role->id);
        })->whereHas('unpaidCart')
            ->with(['unpaidCart.customer_orders.panel', 'roles'])
            ->paginate(5)->appends(request()->query());
        $customers_data = ResourcesUser::collection($unpaid_customers_collection)->resolve();

        return response()->json(['message' => 'Clients ayant panier impayé', 'data' => $customers_data]);
    }

    /**
     * GET: Orders list (paginated)
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\Http\Response
     */
    public function getOrders(Request $request)
    {
        // $orders = CustomerOrder::with('face.panel', 'user')
        //     ->whereHas('cart', function ($query) {
        //         $query->where('is_paid', 0);
        //     })->orderByDesc('created_at')->paginate(10)->appends($request->query());

        // return response()->json([
        //     'orders' => ResourcesCustomerOrder::collection($orders)->resolve(),
        //     'total_pages' => $orders->lastPage(),
        // ]);
    }

    /**
     * GET: Selected order
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function getOrderDetails($id)
    {
        // $order = CustomerOrder::with('panel', 'user')->find($id);

        // return response()->json(new ResourcesCustomerOrder($order));
    }

    /**
     * GET: Panels list page
     *
     * @return \Illuminate\View\View
     */
    public function panels()
    {
        // panels
        $panels_collection = request()->has('is_available') ? Panel::where('is_available', '=', request()->get('is_available'))->orderByDesc('created_at')->paginate(5)->appends(request()->query()) : Panel::orderByDesc('created_at')->paginate(5)->appends(request()->query());
        $panels_data = ResourcesPanel::collection($panels_collection)->resolve();

        return view('panels', [
            'panels' => $panels_data,
            'panels_req' => $panels_collection
        ]);
    }

    /**
     * GET: Expenses list page
     *
     * @return \Illuminate\View\View
     */
    public function expenses()
    {
        // expenses
        $expenses_collection = Expense::orderByDesc('created_at')->paginate(5)->appends(request()->query());
        $expenses_data = ResourcesExpense::collection($expenses_collection)->resolve();

        return view('expenses', [
            'expenses' => $expenses_data,
            'expenses_req' => $expenses_collection,
        ]);
    }

    /**
     * GET: Users list page
     *
     * @return \Illuminate\View\View
     */
    public function users()
    {
        // roles
        $roles = Role::all();
        // role "Administrateur"
        $admin_role = Role::where('role_name', 'Administrateur')->first();
        // users
        $users_collection = request()->has('status') ? User::where([['id', '<>', Auth::user()->id], ['is_active', '=', request()->get('status')]])->orderByDesc('created_at')->paginate(5)->appends(request()->query()) : User::where('id', '<>', Auth::user()->id)->orderByDesc('created_at')->paginate(5)->appends(request()->query());
        $users_data = ResourcesUser::collection($users_collection)->resolve();

        return view('users', [
            'roles' => $roles,
            'users' => $users_data,
            'users_req' => $users_collection,
            'admin' => $admin_role
        ]);
    }

    /**
     * GET: Users list page
     *
     * @param  string $entity
     * @return \Illuminate\View\View
     */
    public function usersEntity($entity)
    {
        if (!in_array($entity, ['roles', 'orders', 'search'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        // roles
        $roles = Role::all();
        // role "Client"
        $customer_role = Role::where('role_name', 'Client')->first();

        if (!$customer_role) {
            $customer_role = Role::create([
                'role_name' => 'Client',
                'role_description' => 'Personne ou entreprise louant des panneaux'
            ]);
        }

        // if ($entity == 'orders') {
        //     // panels
        //     $available_panels_collection = Panel::with(['faces' => function ($query) {
        //         $query->where('is_available', 1);
        //     }])->whereHas('faces', function ($query) {
        //         $query->where('is_available', 1);
        //     })->orderByDesc('created_at')->get();

        //     $available_panels_data = ResourcesPanel::collection($available_panels_collection)->resolve();
        //     $count_customers = User::whereHas('roles', function ($query) use ($customer_role) {
        //         $query->where('roles.id', $customer_role->id);
        //     })->count();
        //     $customers_ids = User::whereHas('roles', function ($query) use ($customer_role) {
        //         $query->where('roles.id', $customer_role->id);
        //     })->pluck('id')->toArray();
        //     $carts_collection = Cart::with(['customer_orders.user', 'customer_orders.face', 'customer_orders.expenses'])
        //         ->join('customer_orders', 'carts.id', '=', 'customer_orders.cart_id')
        //         ->whereIn('customer_orders.user_id', $customers_ids)
        //         ->select('carts.id', 'carts.payment_code', 'carts.is_paid', 'carts.created_at', 'carts.updated_at')
        //         ->groupBy('carts.id', 'carts.payment_code', 'carts.is_paid', 'carts.created_at', 'carts.updated_at')
        //         ->orderBy('carts.created_at', 'desc')
        //         ->paginate(5)->appends(request()->query());
        //     $carts_data = ResourcesCart::collection($carts_collection)->resolve();

        //     // page title
        //     $entity_title = 'Locations des clients';

        //     return view('users', [
        //         'roles' => $roles,
        //         'customer' => $customer_role,
        //         'count_customers' => $count_customers,
        //         'carts' => $carts_data,
        //         'carts_req' => $carts_collection,
        //         'available_panels' => $available_panels_data,
        //         'entity' => $entity,
        //         'entity_title' => $entity_title
        //     ]);
        // }

        if ($entity == 'roles') {
            // page title
            $entity_title = 'Gérer les rôles';

            return view('users', [
                'roles' => $roles,
                'entity' => $entity,
                'entity_title' => $entity_title
            ]);
        }

        if ($entity == 'search') {
            // Search users with role "Client"
            $search = request()->get('q');

            if (!$search) {
                return response()->json([
                    'status' => 'empty',
                    'data' => [],
                ]);
            }

            // ============= Une recherche plus chirurgicale ============
            // $customers = User::whereHas('roles', function ($query) use ($customer_role) {
            //                         $query->where('roles.id', $customer_role->id);
            //                     })->when($search, function ($query, $search) {
            //                         $search = trim($search);
            //                         $keywords = preg_split('/\s+/', $search); // split by space

            //                         $query->where(function ($q) use ($keywords) {

            //                             foreach ($keywords as $keyword) {
            //                                 $q->where(function ($sub) use ($keyword) {
            //                                     $sub->where('firstname', 'LIKE', $keyword . '%')
            //                                     ->orWhere('lastname', 'LIKE', $keyword . '%')
            //                                     ->orWhereRaw("CONCAT(firstname, ' ', lastname) LIKE ?", [$keyword . '%']);
            //                                 });
            //                             }
            //                         });
            //                     })->orderBy('firstname')->limit(15)->get();

            $customers = User::whereHas('roles', function ($query) use ($customer_role) {
                $query->where('roles.id', $customer_role->id);
            })->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('firstname', 'LIKE', '%' . $search . '%')->orWhere('lastname', 'LIKE', '%' . $search . '%');
                });
            })->orderBy('firstname')->get();

            if ($customers->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Aucun client trouvé.'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Clients trouvés.',
                'data' => ResourcesUser::collection($customers)->resolve()
            ]);
        }
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
        // $expense_order = null;

        if (is_null($expense)) {
            return redirect('/expenses')->with('error_message', 'Dépense non trouvée.');
        }

        // if (!empty($expense->customer_order_id)) {
        //     $expense_order = CustomerOrder::find($expense->customer_order_id);

        //     if (is_null($expense_order)) {
        //         return redirect('/expenses')->with('error_message', 'Location non trouvée.');
        //     }
        // }

        return view('expenses', [
            'selected_expense' => new ResourcesExpense($expense),
            // 'expense_order' => $expense_order != null ? new ResourcesCustomerOrder($expense_order) : null,
        ]);
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
        if (!in_array($entity, ['roles', 'cart'])) {
            return redirect(RouteServiceProvider::HOME)->with('error_message', 'Il n\'y a aucun lien de ce genre.');
        }

        if ($entity == 'roles') {
            $role = Role::find($id);

            if (!$role) {
                return redirect(RouteServiceProvider::HOME)->with('error_message', 'Rôle non trouvé.');
            }

            return view('users', [
                'selected_role' => new ResourcesRole($role),
                'entity' => $entity,
                'entity_title' => $role->role_name
            ]);
        }

        // if ($entity == 'cart') {
        //     $cart = Cart::find($id);

        //     if (!$cart) {
        //         return redirect(RouteServiceProvider::HOME)->with('error_message', 'Locations non trouvées.');
        //     }

        //     $customer_orders = $cart->customer_orders;

        //     return view('users', [
        //         'selected_cart' => (new ResourcesCart($cart))->toArray(request()),
        //         'entity' => $entity,
        //         'entity_title' => 'Location de ' . $customer_orders[0]->user->firstname
        //     ]);
        // }
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
     * GET: Delete something
     *
     * @param  string $entity
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function removeData(Request $request, $entity, $id)
    {
        if ($entity == 'panel') {
            $panel = Panel::find($id);

            if (!$panel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panneau non trouvé',
                ], 404);
            }

            $facesToDelete = Face::where('panel_id', $panel->id)->get();
            $filesToDelete = File::where('panel_id', $panel->id)->get();

            if (count($facesToDelete) > 0) {
                foreach ($facesToDelete as $face) {
                    // Deletes the row at the database
                    $face->delete();
                }
            }

            if (count($filesToDelete) > 0) {
                foreach ($filesToDelete as $file) {
                    // Delete the file from the file system
                    $relativeStoragePath = str_replace(getWebURL() . '/storage/', '', $file->file_url);

                    Storage::disk('public')->delete($relativeStoragePath);

                    // Deletes the row at the database
                    $file->delete();
                }
            }

            $panel->delete();

            return response()->json([
                'success' => true,
                'message' => 'Panneau supprimé',
            ]);
        }

        if ($entity == 'face') {
            $face = Face::find($id);

            if (!$face) {
                return response()->json([
                    'success' => false,
                    'message' => 'Face de panneau non trouvée',
                ], 404);
            }

            $panel = $face->panel;

            if (!$panel) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panneau non trouvé',
                ], 404);
            }

            $panel_faces = $panel->faces;

            if (count($panel_faces) == 2) {
                $panel_format_text = explode(' ', $panel->format)[0];
                $panel_new_format = $panel_format_text . ' (1 face)';

                $panel->update(
                    ['format' => $panel_new_format]
                );

                $face->delete();
            } elseif (count($panel_faces) == 1) {
                $panel->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Face de panneau supprimée',
            ]);
        }

        if ($entity == 'expense') {
            $expense = Expense::find($id);

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dépense non trouvée',
                ], 404);
            }

            // Withdraw expense
            $expense->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dépense supprimée',
            ]);
        }

        if ($entity == 'user') {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Utilisateur non trouvé',
                ], 404);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé',
            ]);
        }

        if ($entity == 'role') {
            $role = Role::find($id);

            if (!$role) {
                return response()->json([
                    'success' => false,
                    'message' => 'Rôle non trouvé',
                ], 404);
            }

            $role->delete();

            return response()->json([
                'success' => true,
                'message' => 'Rôle supprimé',
            ]);
        }

        // if ($entity == 'order') {
        //     // Check if cart exists, is unpaid
        //     $cart = Cart::where([['id', $request->cart_id], ['is_paid', 0]])->latest()->first();

        //     if (!$cart) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Location de panneau non trouvée',
        //         ], 404);
        //     }

        //     // Get the ordered face with the pivot relationship
        //     $customer_order = CustomerOrder::where([['cart_id', $cart->id], ['face_id', $id]])->first();

        //     if (!$customer_order) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Face de panneau non trouvée dans la location',
        //         ], 404);
        //     }

        //     // Retrieve the face in the stock
        //     $in_stock_face = Face::find($customer_order->face_id);

        //     if (!$in_stock_face) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Face de panneau introuvable dans le stock',
        //         ], 404);
        //     }

        //     // Update the face in the stock
        //     $in_stock_face->update([
        //         'is_available' => 1
        //     ]);

        //     $customer_order->delete();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Face de panneau retirée de la location',
        //     ]);
        // }

        // if ($entity == 'cart') {
        //     // Check the existence of the unpaid cart containing the panel order
        //     $cart = Cart::find('id');

        //     if (!$cart) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Locations non trouvées',
        //         ], 404);
        //     }

        //     $ordersToDelete = CustomerOrder::where('cart_id', $cart->id)->get();
        //     $accountancy = Accountancy::where('cart_id', $cart->id)->first();

        //     if (count($ordersToDelete) > 0) {
        //         // Withdraw orders based on cart
        //         foreach ($ordersToDelete as $order) {
        //             // Deletes the row at the database
        //             $order->delete();
        //         }
        //     }

        //     // Withdraw cart & accountancy
        //     $accountancy->delete();
        //     $cart->delete();

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'Locations supprimées',
        //     ]);
        // }
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
            $validated['birthdate'] = Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Password hash if present
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);

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
                $updateData['former_password'] = $request->password;

                $password_reset->update($updateData);
            }
        }

        // Processing of the base64 image if present
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_path = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_path, base64_decode($image));

            $validated['avatar_url'] = Storage::url($image_path);

            unset($validated['image_64']);
        }

        // Update user with valid fields
        $user->update($validated);

        // Conditional return: AJAX or HTML POST
        return $request->expectsJson()
            ? response()->json(['success_message' => true, 'avatar_url' => $user->avatar_url ?? null])
            : back()->with('success_message', 'Vos informations ont bien été mises à jour.');
    }

    /**
     * GET: Delete something
     *
     * @param  string $entity
     * @param  int $id
     * @throws \Illuminate\Http\RedirectResponse
     */
    public function changeData(Request $request, $entity, $id)
    {
        if ($entity == 'expense') {
            $expense = Expense::find($id);

            if (!$expense) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dépense non trouvée',
                ], 404);
            }

            if ($request->filled('designation')) {
                $expense->update(['designation' => $request->designation]);
            }

            if ($request->filled('amount')) {
                $expense->update(['amount' => $request->amount]);
            }

            if ($request->filled('outflow_date')) {
                $expense->update(['outflow_date' => $request->outflow_date]);
            }

            if ($request->filled('panel_id')) {
                $expense->update(['panel_id' => $request->panel_id]);
            }

            if ($request->filled('rented_face_id')) {
                $expense->update(['rented_face_id' => $request->rented_face_id]);
            }

            if ($request->filled('month_data_id')) {
                $expense->update(['month_data_id' => $request->month_data_id]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dépense modifiée',
            ]);
        }

        if ($entity == 'month_data') {
            $monthData = MonthData::find($id);

            if (!$monthData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données du mois non trouvées',
                ], 404);
            }

            if ($request->filled('month')) {
                $monthData->update(['month' => $request->month]);
            }

            if ($request->filled('year')) {
                $monthData->update(['year' => $request->year]);
            }

            if ($request->filled('remaining_amount')) {
                $monthData->update(['remaining_amount' => $request->remaining_amount]);
            }

            if ($request->filled('tithe_paid')) {
                $monthData->update(['tithe_paid' => $request->tithe_paid]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Données du mois modifiées',
            ]);
        }

        if ($entity == 'tithe_paid') {
            $monthData = MonthData::find($id);

            if (!$monthData) {
                return response()->json([
                    'success' => false,
                    'message' => 'Données du mois non trouvées',
                ], 404);
            }

            $tithePaid = $monthData->tithe_paid == 0 ? 1 : 0;

            $monthData->update(['tithe_paid' => $tithePaid]);

            return response()->json([
                'success' => true,
                'message' => 'Paiement dîme modifié',
            ]);
        }
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
            'dimensions' => ['required', 'string', 'max:255'],
            'format' => ['required', 'string'],
            // 'price' => ['required', 'numeric', 'between:0,9999999.99'],
            'location' => ['required', 'string'],
        ], [
            'dimensions.required' => 'Veuillez mettre les dimensions.',
            'dimensions.unique' => 'Cette dimension existe déjà.',
            'format.required' => 'Le format est obligatoire.',
            // 'unit_price.required' => 'Le prix est obligatoire.',
            'location.required' => 'Veuillez donner son emplacement.',
        ]);

        $faces_text = $request->number_of_faces == 2 ? 'faces' : 'face';
        $panel = Panel::create([
            'dimensions' => $request->dimensions,
            'format' => $request->format . ' (' . $request->number_of_faces . ' ' . $faces_text . ')',
            // 'price' => $request->price,
            'location' => $request->location,
            'created_by' => Auth::id(),
        ]);

        if ($request->number_of_faces == 1) {
            Face::create([
                'face_name' => 'Recto',
                'is_available' => !empty($request->is_available) ? $request->is_available : 1,
                'panel_id' => $panel->id,
            ]);
        } else if ($request->number_of_faces == 2) {
            Face::create([
                'face_name' => 'Recto',
                'is_available' => !empty($request->is_available) ? $request->is_available : 1,
                'panel_id' => $panel->id,
            ]);
            Face::create([
                'face_name' => 'Verso',
                'is_available' => !empty($request->is_available) ? $request->is_available : 1,
                'panel_id' => $panel->id,
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Panneau ajouté avec succès.']);
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
            'amount' => ['required', 'numeric', 'between:0,9999999.99'],
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

        Expense::create([
            'designation' => $request->designation,
            'amount' => $request->amount,
            'outflow_date' => $outflow,
            'created_by' => Auth::id(),
            'panel_id' => $request->panel_id,
            'rented_face_id' => $request->rented_face_id,
            'month_data_id' => $request->month_data_id,
        ]);

        return response()->json(['status' => 'success', 'message' => 'Dépense ajoutée avec succès.']);
    }

    /**
     * POST: Add a user
     *
     * @param  \Illuminate\Http\Request  $request
     * @throws \Illuminate\Http\Response
     */
    public function addUser(Request $request)
    {
        $random_int_stringified = (string) random_int(1000000, 9999999);

        // Validate fields
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'username' => ['string', 'username', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'firstname.required' => 'Le prénom est obligatoire.',
            'email.email' => 'Le format de l\'email est invalide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'phone.required' => 'Le n° de téléphone est obligatoire.',
            'username.unique' => 'Ce nom d\'utilisateur est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
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
            $image_path = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            // Upload image
            Storage::disk('public')->put($image_path, base64_decode($image));

            $user->update([
                'avatar_url' => Storage::url($image_path),
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

        return response()->json(['status' => 'success', 'message' => 'Utilisateur ajouté avec succès.']);
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
                'role_name.required' => 'Le nom du rôle est obligatoire.'
            ]);

            // Register role
            Role::create([
                'role_name' => $request->role_name,
                'role_description' => $request->role_description,
                'created_by' => Auth::user()->id,
            ]);
        }

        // if ($entity == 'orders') {
        //     // Vérification si un utilisateur existant est sélectionné
        //     $isExistingCustomer = $request->filled('customer_phone');

        //     // Validation de base
        //     $rules = [
        //         'firstname' => ['required', 'string', 'max:255'],
        //         'phone'     => ['required', 'string', 'max:45', 'unique:users'],
        //         'email'     => ['nullable', 'string', 'email', 'max:255'],
        //     ];

        //     if (!$isExistingCustomer) {
        //         $rules['phone'][] = 'unique:users';

        //         $request->validate($rules, [
        //             'firstname.required' => 'Le prénom est obligatoire.',
        //             'phone.required'     => 'Le n° de téléphone est obligatoire.',
        //         ]);
        //     }

        //     DB::beginTransaction();

        //     try {
        //         // Rechercher le client existant
        //         $customer = null;

        //         if ($isExistingCustomer) {
        //             $customer = User::where('phone', $request->customer_phone)->first();
        //         }

        //         // Si le client n'existe pas, on le crée
        //         if (!$customer) {
        //             $random_int_token = (string) random_int(1000000, 9999999);
        //             $random_string_password = (string) Str::random();

        //             $customer = User::create([
        //                 'firstname' => $request->firstname,
        //                 'lastname'  => $request->lastname,
        //                 'email'     => $request->email,
        //                 'phone'     => $request->phone,
        //                 'password'  => Hash::make($random_string_password),
        //             ]);

        //             PasswordReset::create([
        //                 'email'           => $request->email,
        //                 'phone'           => $request->phone,
        //                 'token'           => $random_int_token,
        //                 'former_password' => $random_string_password
        //             ]);

        //             // Rôle client
        //             $customer_role = Role::firstOrCreate(
        //                 ['role_name' => 'Client'],
        //                 ['role_description' => 'Personne ou entreprise louant des panneaux']
        //             );

        //             $customer->roles()->attach($customer_role->id);

        //             // Traitement de l'image
        //             if ($request->filled('image_64')) {
        //                 $replace = substr($request->image_64, 0, strpos($request->image_64, ',') + 1);
        //                 $image = str_replace([$replace, ' '], ['', '+'], $request->image_64);
        //                 $image_path = 'images/users/' . $customer->id . '/avatar/' . Str::random(50) . '.png';

        //                 Storage::disk('public')->put($image_path, base64_decode($image));

        //                 $customer->update([
        //                     'avatar_url' => Storage::url($image_path),
        //                     'updated_at' => now()
        //                 ]);
        //             }
        //         }

        //         // Création du panier
        //         $cart = Cart::create([
        //             'payment_code' => Str::random(10),
        //             'is_paid'      => 0,
        //         ]);

        //         if (!$request->filled('panels_ids') || !is_array($request->panels_ids)) {
        //             DB::rollBack();

        //             return response()->json(['status' => 'error', 'message' => 'Veuillez choisir au moins un panneau.'], 422);
        //         }

        //         foreach ($request->panels_ids as $key => $face_id) {
        //             $face = Face::find($face_id);

        //             if (!$face || !$face->is_available) {
        //                 DB::rollBack();

        //                 return response()->json(['status' => 'error', 'message' => 'Le panneau est déjà commandé.']);
        //             }

        //             $end_date = null;

        //             if (isset($request->end_date[$key]) && !empty($request->end_date[$key])) {
        //                 $parts = explode(' ', $request->end_date[$key]); // ['30/05/2025', '14:30']

        //                 if (count($parts) === 2) {
        //                     [$day, $month, $year] = explode('/', $parts[0]);
        //                     $time = $parts[1];
        //                     $end_date = "$year-$month-$day $time:00"; // DATETIME format
        //                 }
        //             }

        //             if (!$end_date) {
        //                 $end_date = now()->addDays(1);
        //             }

        //             $customer_order = CustomerOrder::create([
        //                 'price_at_that_time' => $face->panel->price,
        //                 'end_date' => $end_date,
        //                 'user_id'            => $customer->id,
        //                 'face_id'           => $face->id,
        //                 'cart_id'            => $cart->id,
        //             ]);

        //             // Get number of days via "end_date"
        //             $date1 = new Carbon($customer_order->created_at);
        //             $date2 = new Carbon($customer_order->end_date);
        //             $duration = $date1->diff($date2);
        //             // Calculate total price
        //             $count_duration = ($duration->d == 0 ? 1 : $duration->d);
        //             $total_price = $customer_order->price_at_that_time * $count_duration;

        //             $expense = Expense::create([
        //                 'designation'       => 'Dîme (10%)',
        //                 'amount'            => $total_price / 10,
        //                 'outflow_date'      => now(),
        //                 'created_by'        => Auth::id(),
        //                 'customer_order_id' => $customer_order->id,
        //             ]);

        //             Accountancy::create([
        //                 'expense_id' => $expense->id
        //             ]);

        //             $face->update(['is_available' => 0]);
        //         }

        //         Accountancy::create(['cart_id' => $cart->id]);
        //         DB::commit();

        //         return response()->json(['status' => 'success', 'message' => 'Location ajoutée avec succès.']);
        //     } catch (\Exception $e) {
        //         DB::rollBack();

        //         // Log optionnel
        //         Log::error('Erreur panier : ' . $e->getMessage());
        //         return response()->json(['status' => 'error', 'message' => 'Erreur lors de la création du panier.']);
        //     }
        // }

        return response()->json(['status' => 'success', 'message' => 'Données ajoutées avec succès.']);
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

        if ($request->has('price')) {
            $rules['price'] = ['nullable', 'numeric', 'between:0,9999999.99'];
        }

        if ($request->has('location')) {
            $rules['location'] = ['nullable', 'string', 'max:65535'];
        }

        if ($request->hasFile('images_urls')) {
            $rules['images_urls.*'] = ['nullable', 'file', 'mimes:jpg,jpeg,png,bmp,gif', 'max:2048'];
            $rules['file_name'] = ['nullable', 'string'];
        }

        if ($request->has('deleted_file_ids')) {
            $rules['deleted_file_ids'] = ['array'];
            $rules['deleted_file_ids.*'] = ['integer', 'exists:files,id'];
        }

        // Gestion des faces : ajout/suppression/modification des faces
        $existing_faces = Face::where('panel_id', $panel->id)->get();
        $current_format = $panel->format;
        $current_faces_count = $existing_faces->count();  // Nombre actuel de faces du panneau

        // Étape 1 : Mettre à jour le format uniquement si le nombre de faces change
        if ($request->number_of_faces != $current_faces_count) {
            // Si le format contient "(1 face)", on le remplace par "(2 faces)" ou inversement
            if (strpos($current_format, '(1 face)') !== false) {
                $new_format = str_replace('(1 face)', '(2 faces)', $current_format);
                $panel->format = $new_format;
            } elseif (strpos($current_format, '(2 faces)') !== false) {
                $new_format = str_replace('(2 faces)', '(1 face)', $current_format);
                $panel->format = $new_format;
            }
        }

        // Étape 2 : Gestion des faces
        if ($request->number_of_faces == 1) {
            // Si le panneau avait 2 faces, on supprime la face "Verso"
            if ($existing_faces->count() == 2) {
                Face::where('panel_id', $panel->id)->where('face_name', 'Verso')->delete();
            }

            // Assure-toi qu'il y a au moins la face "Recto"
            if ($existing_faces->count() == 0) {
                Face::create([
                    'face_name' => 'Recto',
                    'is_available' => !empty($request->is_available) ? $request->is_available : 1,
                    'panel_id' => $panel->id,
                ]);
            }
        } else if ($request->number_of_faces == 2) {
            // Si le panneau avait une seule face, on ajoute la face "Verso" ou "Recto"
            if ($existing_faces->count() == 1) {
                $existing_face = $existing_faces->first();
                $new_face_name = $existing_face->face_name == 'Recto' ? 'Verso' : 'Recto';

                // Création de la nouvelle face
                Face::create([
                    'face_name' => $new_face_name,
                    'is_available' => !empty($request->is_available) ? $request->is_available : 1,
                    'panel_id' => $panel->id,
                ]);
            }
        }

        // Mise à jour des autres champs du panneau
        $panel->update([
            'dimensions' => $request->dimensions ?? $panel->dimensions,
            'price' => $request->price ?? $panel->price,
            'location' => $request->location ?? $panel->location,
            'updated_by' => Auth::id(),
        ]);

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
     * POST: Update expense
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

        if ($request->has('customer_order_id')) {
            $rules['customer_order_id'] = ['nullable', 'numeric'];
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
            $validated['birthdate'] = Carbon::createFromFormat('d/m/Y', $validated['birthdate'])->format('Y-m-d');
        }

        // Password hash if present
        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);

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
                $updateData['former_password'] = $validated['password'];

                $password_reset->update($updateData);
            }
        }

        // Processing of the base64 image if present
        if (isset($validated['image_64'])) {
            $replace = substr($validated['image_64'], 0, strpos($validated['image_64'], ',') + 1);
            $image = str_replace($replace, '', $validated['image_64']);
            $image = str_replace(' ', '+', $image);

            $image_path = 'images/users/' . $user->id . '/avatar/' . Str::random(50) . '.png';

            Storage::disk('public')->put($image_path, base64_decode($image));

            $validated['avatar_url'] = Storage::url($image_path);

            unset($validated['image_64']);
        }

        // Update user with valid fields
        $user->update($validated);

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
        if (!in_array($entity, ['roles', 'cart'])) {
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

        // if ($entity == 'cart') {
        //     $cart = Cart::find($id);

        //     if (!$cart) {
        //         return back()->with('error_message', 'Panier non trouvé.');
        //     }

        //     $updates = [];
        //     $message = 'Mise à jour terminée.';

        //     if ($request->filled('is_paid')) {
        //         $updates['is_paid'] = $request->is_paid;

        //         if ($request->is_paid == 1) {
        //             $updates['payment_code'] = (string) random_int(1000000, 9999999);
        //             $message = 'Locations payées.';
        //         } else {
        //             $updates['payment_code'] = null;
        //         }
        //     }

        //     foreach ($cart->customer_orders as $order) {
        //         $order->face->update(['is_available' => 1]);
        //     }

        //     if (!empty($updates)) {
        //         $cart->update($updates);

        //         return back()->with('success_message', $message);
        //     }

        //     // No fields to update
        //     return back()->with('error_message', 'Aucune donnée à mettre à jour.');
        // }
    }
}