<?php



namespace App\Http\Controllers\employee;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEmployee;
use App\Http\Requests\UpdateEmployee;
use App\Models\Employee;
use App\Models\Position;
use App\Models\Schedule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EmployeesExport;

use App\Models\Notification;
use Carbon\Carbon;



use App\Imports\EmployeeImport;







class employeeController extends Controller
{

    public function importAndUpgrade(Request $request)
    {
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,xls,csv',
        ]);

        // 1. Importer les données Excel
        Excel::import(new EmployeeImport, $request->file('excel_file'));

        // 2. Mise à jour automatique
        $today = Carbon::today();
        $employees = Employee::all();
        $count = 0;

        foreach ($employees as $employee) {
            if (!$employee->grade || !$employee->ech || !$employee->date_effet1) {
                continue;
            }

            $dateEffet = Carbon::parse($employee->date_effet1);
            $dueDate = $dateEffet->copy()->addYears(2)->startOfYear();

            if ($today->greaterThanOrEqualTo($dueDate)) {

                $ancien_grade = $employee->grade;
                $ancien_echelon = $employee->ech;
                $nouveau_grade = $ancien_grade;
                $nouveau_echelon = $ancien_echelon;

                if ($ancien_grade == 'MC') {
                    if ($ancien_echelon < 3) {
                        $nouveau_echelon++;
                    } else {
                        $nouveau_grade = 'MCH';
                        $nouveau_echelon = 1;
                    }
                } elseif ($ancien_grade == 'MCH') {
                    if ($ancien_echelon < 4) {
                        $nouveau_echelon++;
                    } else {
                        $nouveau_grade = 'PES';
                        $nouveau_echelon = 1;
                    }
                } elseif ($ancien_grade == 'PES') {
                    if ($ancien_echelon < 6) {
                        $nouveau_echelon++;
                    } else {
                        continue;
                    }
                }

                // Vérification doublon dans notifications
                $notifExiste = Notification::where('employee_id', $employee->id)
                    ->where('nouveau_grade', $nouveau_grade)
                    ->where('nouveau_echelon', $nouveau_echelon)
                    ->exists();

                if ($notifExiste) continue;

                // Mise à jour de l’employé
                $employee->update([
                    'grade' => $nouveau_grade,
                    'ech' => $nouveau_echelon,
                    'date_effet1' => Carbon::createFromDate($today->year + 2, 1, 1),
                ]);

                // Création notification
                Notification::create([
                    'employee_id' => $employee->id,
                    'ancien_grade' => $ancien_grade,
                    'nouveau_grade' => $nouveau_grade,
                    'ancien_echelon' => $ancien_echelon,
                    'nouveau_echelon' => $nouveau_echelon,
                    'date_changement' => now(),
                    'message' => "L’enseignant " . $employee->NOM_ET_PRENOM . " est passé au grade $nouveau_grade, échelon $nouveau_echelon le " . now()->format('d/m/Y'),
                ]);

                // Envoi email promotion (optionnel)
                // $employee->notify(new GradePromotionNotification($nouveau_grade));

                $count++;
            }
        }

        return back()->with('success', "Fichier importé et mise à jour effectuée avec succès ($count évolution(s)).");
    }





//     public function updateEchelonsManuellement()
// {
//     $today = Carbon::now();
//     $employees = Employee::all();
//     $count = 0;

//     foreach ($employees as $employee) {
//         if (!$employee->grade || !$employee->ech || !$employee->date_effet1) {
//             continue;
//         }

//         $dateEffet = Carbon::parse($employee->date_effet1);
//         $dueDate = $dateEffet->copy()->addYears(2)->startOfYear();

//         if ($today->greaterThanOrEqualTo($dueDate)) {

//             $ancien_grade = $employee->grade;
//             $ancien_echelon = $employee->ech;
//             $nouveau_grade = $ancien_grade;
//             $nouveau_echelon = $ancien_echelon;

//             if ($ancien_grade == 'MC') {
//                 if ($ancien_echelon < 3) {
//                     $nouveau_echelon++;
//                 } else {
//                     $nouveau_grade = 'MCH';
//                     $nouveau_echelon = 1;
//                 }
//             } elseif ($ancien_grade == 'MCH') {
//                 if ($ancien_echelon < 4) {
//                     $nouveau_echelon++;
//                 } else {
//                     $nouveau_grade = 'PES';
//                     $nouveau_echelon = 1;
//                 }
//             } elseif ($ancien_grade == 'PES') {
//                 if ($ancien_echelon < 6) {
//                     $nouveau_echelon++;
//                 } else {
//                     continue;
//                 }
//             }

//             // Vérification doublon
//             $notifExiste = Notification::where('employee_id', $employee->id)
//                 ->where('nouveau_grade', $nouveau_grade)
//                 ->where('nouveau_echelon', $nouveau_echelon)
//                 ->exists();

//             if ($notifExiste) continue;

//             $employee->update([
//                 'grade' => $nouveau_grade,
//                 'ech' => $nouveau_echelon,
//                 'date_effet1' => Carbon::createFromDate($today->year + 2, 1, 1),
//             ]);

//             Notification::create([
//                 'employee_id' => $employee->id,
//                 'ancien_grade' => $ancien_grade,
//                 'nouveau_grade' => $nouveau_grade,
//                 'ancien_echelon' => $ancien_echelon,
//                 'nouveau_echelon' => $nouveau_echelon,
//                 'date_changement' => now(),
//                 'message' => "L’enseignant " . $employee->NOM_ET_PRENOM . " est passé au grade $nouveau_grade, échelon $nouveau_echelon le " . now()->format('d/m/Y'),

//             ]);

//             $count++;
//         }
//     }

//     return redirect()->back()->with('success', "✅ Mise à jour terminée ($count évolution(s)).");
// }







public function notifications(Request $request)
{
    $query = Notification::with('employee');

    if ($request->filled('year')) {
        $query->whereYear('date_changement', $request->year);
    }

    if ($request->filled('employee_id')) {
        $query->where('employee_id', $request->employee_id);
    }

    $notifications = $query->latest()->paginate(); // ✅ pagination correcte
    $employees = \App\Models\Employee::select('id', 'NOM_ET_PRENOM')->get();

    return view('notifications.index', compact('notifications', 'employees'));
}



    public function generateExcel()
    {
        // Chemin pour stocker le fichier dans le répertoire public de Laravel
        $filePath = storage_path('app/public/exports/nom1.xlsx');

        // Sauvegarder le fichier dans ce dossier
        Excel::store(new EmployeesExport, $filePath, 'public');

        return back()->with('success', '✅ Le fichier Excel a été mis à jour avec succès.');
    }
    public function destroy(Employee $employee)
{
    $employee->forceDelete(); // supprime VRAIMENT de la base

    return redirect()->route('employee.index')->with('success', 'Suppression définitive réussie.');
}

    /**
     * Display a listing of the resource.
     */




     public function index(Request $request)
     {
         $query = Employee::with('position');

         // 🔍 فلترة حسب الاسم أو البريد
         if ($request->filled('search')) {
             $query->where(function ($q) use ($request) {
                 $q->where('first_name', 'like', '%' . $request->search . '%')
                   ->orWhere('last_name', 'like', '%' . $request->search . '%')
                   ->orWhere('email', 'like', '%' . $request->search . '%');
             });
         }

         // 📌 فلترة حسب المنصب
         if ($request->filled('position')) {
             $query->where('position_id', $request->position);
         }

         // 💰 ترتيب حسب الراتب
         if ($request->filled('salary_sort')) {
             $query->orderBy('salary', $request->salary_sort);
         }

         // 🔢 ترقيم النتائج
         $data = $query->paginate(10)->appends($request->all());

         // 🧮 أعلى راتب في كل منصب
         $maxSalaries = Employee::selectRaw('position_id, MAX(salary) as max_salary')
             ->groupBy('position_id')
             ->pluck('max_salary', 'position_id');

         return view('private.employee.index', compact('data', 'maxSalaries'));
     }







    /**
     * Show the form for creating a new resource.
     */
//     public function create()
//     {
//         $positions = Position::all();
//         return view("private.employee.create", compact("positions"));
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(CreateEmployee $request)
//     {
//         $data = $request->validated();

//         if (isset($data['training'])) {
//             if ($data['training'] == "on") {
//                 $data['training'] = 1;
//             }
//         } else {
//             $data['training'] = 0;
//         }
//         $cvPath = null;
//         if ($request->hasFile('cv')) {
//             $cvPath = $request->file('cv')->storePublicly('cv', 'public');
//         }
//         $imagePath = null;
//         if ($request->hasFile('image')) {
//             $imagePath = $request->file('image')->storePublicly('images', 'public');
//         }

//         $employee = new Employee();
//         $employee->fill($data);
//         $employee->cv = $cvPath;
//         $employee->image = $imagePath;
//         $employee->position_id = $request->input('position');
//         $employee->start_date = now();
//         $employee->save();
//         return redirect()->route('employee.index')->with('success', 'Employee created successfully.');
//     }

//     /**
//      * Display the specified resource.
//      */
//     // Affichage des détails d'un employé
// public function show($id)
// {
//     $employee = Employee::with('position')->findOrFail($id);
//     return view('private.employee.show', compact('employee'));
// }

// // Formulaire de modification
// public function edit($id)
// {
//     $employee = Employee::findOrFail($id);
//     $positions = Position::all();
//     return view('private.employee.edit', compact('employee', 'positions'));
// }

// // Mise à jour des données
// public function update(Request $request, $id)
// {
//     $request->validate([
//         'first_name' => 'required|string|max:255',
//         'last_name' => 'required|string|max:255',
//         'email' => 'required|email',
//         'phone_number' => 'nullable|string|max:20',
//         'salary' => 'nullable|numeric',
//         'position_id' => 'nullable|exists:positions,id',
//         // Ajoute d'autres validations si nécessaire
//     ]);

//     $employee = Employee::findOrFail($id);
//     $employee->update($request->all());

//     return redirect()->route('employee.index')->with('success', 'Employé mis à jour avec succès.');
// }
public function create()
{
    return view('private.employee.create');
}

public function store(Request $request)
{
    $request->validate([
        'tpr' => 'required|string|max:255',
        'nomar' => 'required|string|max:255',
        'prenomar' => 'required|string|max:255',
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'sex' => 'required|in:M,F',
        'national_id' => 'nullable|string|max:50',
        'date_of_birth' => 'nullable|date',
        'date_retarite' => 'nullable|date',
        'drmc' => 'nullable|date',
        'drm_att_s' => 'nullable|date',
        'cadre' => 'nullable|string|max:255',
        'date_effet1' => 'nullable|date',
        'ech' => 'nullable|string|max:100',
        'date_effet2' => 'nullable|date',

        'indice' => 'nullable|string|max:100',
        'dep' => 'nullable|string|max:255',
        'specialite' => 'nullable|string|max:255',
    ]);

    $data = $request->all();
    $data['NOM_ET_PRENOM'] = $data['first_name'] . ' ' . $data['last_name'];
    $data['nom_prenom'] = $data['nomar'] . ' ' . $data['prenomar'];

    Employee::create($data);

    return redirect()->route('employee.index')->with('success', '✅ Employé ajouté avec succès.');
}




    /**
     * Display the specified resource.
     */
    // Affichage des détails d'un employé
public function show($id)
{
    $employee = Employee::with('position')->findOrFail($id);
    return view('private.employee.show', compact('employee'));
}

// Formulaire de modification
public function edit($id)
{
    $employee = Employee::findOrFail($id);
    $positions = Position::all();
    return view('private.employee.edit', compact('employee', 'positions'));
}

// Mise à jour des données
public function update(Request $request, $id)
{
    $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'nullable|email',
        'tpr' => 'nullable|string',
        'nomar' => 'nullable|string',
        'prenomar' => 'nullable|string',
        'date_of_birth' => 'nullable|date',
        'drmc' => 'nullable|date',
        'drm_att_s' => 'nullable|date',
        'cadre' => 'nullable|string',
        'date_effet1' => 'nullable|date',
        'date_effet2' => 'nullable|date',
        'date_retarite'=> 'nullable|date',
        'ech' => 'nullable|string',
        'indice' => 'nullable|string',
        'dep' => 'nullable|string',
        'specialite' => 'nullable|string',
        'sex' => 'nullable|string',
        // etc...
    ]);

    $employee = Employee::findOrFail($id);
    $employee->update($request->all());

    return redirect()->route('employee.index')->with('success', 'Employé mis à jour avec succès.');
}



    /**
     * Remove the specified resource from storage.
     */
    // public function destroy(string $id)
    // {
    //     Employee::destroy($id);
    //     return redirect()->route("employee.index");
    // }

    public function showEmployeeStats()
    {
        $totalEmployees = Employee::count();
        // Training status counts
        $trainingInProgress = Employee::where('training', 1)->count();
        $trainingCompleted = Employee::where('training', 2)->count();
        $trainingNotStarted = Employee::where('training', 0)->count();
        // Demographic statistics
        $genderDistribution = Employee::select('gender')
            ->groupBy('gender')
            ->selectRaw('COUNT(*) as count')
            ->pluck('count', 'gender');
        $nationalityDistribution = Employee::select('nationality')
            ->groupBy('nationality')
            ->selectRaw('COUNT(*) as count')
            ->pluck('count', 'nationality');
        // Age distribution
        $ageDistribution = Employee::selectRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) AS age')
            ->get()
            ->groupBy(function ($employee) {
                if ($employee->age >= 50) {
                    return '50+';
                } elseif ($employee->age >= 30) {
                    return '30-49';
                } else {
                    return 'Under 30';
                }
            })
            ->map->count();
        // Salary and employment statistics
        $averageSalary = Employee::average('salary');
        $maxSalary = Employee::max('salary');
        $minSalary = Employee::min('salary');
        // Tenure calculation
        $averageTenure = Employee::whereNotNull('start_date')
            ->selectRaw('AVG(TIMESTAMPDIFF(MONTH, start_date, COALESCE(deleted_at, CURDATE()))) AS average_tenure')
            ->value('average_tenure');
        // Position distribution
        $positionDistribution = Employee::with('position')
            ->get()
            ->groupBy('position.name')
            ->map->count();
        // Employment status
        $activeEmployees = Employee::whereNull('deleted_at')->count();
        $inactiveEmployees = Employee::whereNotNull('deleted_at')->count();
        // Latest hires and exits
        $latestHires = Employee::orderBy('created_at', 'desc')->take(5)->get();
        $latestExits = Employee::onlyTrashed()->orderBy('deleted_at', 'desc')->take(5)->get();

        $positionCounts = Employee::with('position') // Preload position data
            ->get()
            ->groupBy('position.name') // Ensure 'position' is correctly related and 'name' exists
            ->map->count();
        $positionDistribution = Employee::with('position')
            ->get()
            ->groupBy('position.name')
            ->map(function ($group) {
                return $group->count();
            });
        $nationalityDistribution = Employee::groupBy('nationality')
            ->select('nationality', DB::raw('COUNT(*) as count'))
            ->pluck('count', 'nationality');
        return view(
            'private.employee.statistics',
            compact(
                'nationalityDistribution',
                'positionCounts',
                'totalEmployees',
                'trainingInProgress',
                'trainingCompleted',
                'trainingNotStarted',
                'genderDistribution',
                'nationalityDistribution',
                'ageDistribution',
                'averageSalary',
                'maxSalary',
                'minSalary',
                'averageTenure',
                'positionDistribution',
                'activeEmployees',
                'inactiveEmployees',
                'latestHires',
                'latestExits'
            )
        );

    }

    public function showTerminated(Request $request)
    {
        $search = $request->query('search');
        // Query to fetch all employees
        $query = Employee::onlyTrashed()->where('training', 0);
        // Check if there is a search query
        if ($search) {
            // Query to search for employees based on first_name, last_name, or email
            $query->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhereHas('position', function ($query) use ($search) {
                        $query->where('name', 'like', "%$search%");
                    })
                    ->orWhere('email', 'like', "%$search%");
            });
        }
        // Apply the condition for employees not under training
        $query->where("training", 0);
        // Paginate the results
        $data = $query->paginate(10);
        return view("private.employee.terminated", compact("data"));
    }

    public function showTerminatedEmployee($id)
    {
        // Ensure the ID is numeric to prevent SQL injection or unexpected errors
        if (!is_numeric($id)) {
            return redirect()->route("employee.terminated")->with('error', 'Invalid employee ID.');
        }
        // Retrieve only the terminated (soft-deleted) employee with the specific conditions
        $employee = Employee::onlyTrashed()
            ->where('id', $id)
            ->where('training', 0)
            ->first();
        // Check if an employee was found
        if (!$employee) {
            return redirect()->route("employee.terminated")->with('error', 'No terminated employee found with the given ID.');
        }
        // If an employee is found, pass it to the view
        return view("private.employee.showTerminated", compact("employee"));
    }
    public function search(Request $request)
    {
        $query = $request->input('search');

        if (empty($query)) {
            return response()->json([]);
        }

        // Fetch employees where training is either 0 or 1
        $employees = Employee::where(function ($q) use ($query) {
            $q->where('first_name', 'LIKE', "%{$query}%")
                ->orWhere('last_name', 'LIKE', "%{$query}%")
                ->orWhere('email', 'LIKE', "%{$query}%");
        })
            ->where('training', '<>', 2)  // Exclude employees where training is 2
            ->get(['id', 'first_name', 'last_name', 'email']);

        return response()->json($employees);
    }






    public function restore($id)
    {
        $employee = Employee::withTrashed()->findOrFail($id);
        $employee->restore();

        return response()->json(['message' => 'Employee restored successfully!']);
    }

    public function unAssignSchedule(string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->schedule_id = null;
        $employee->update();
        if ($employee->training == 0) {
            return redirect()->route('employee.show', ["employee" => $id])->with('successEdit', 'Employee updated successfully.');
        } elseif ($employee->training == 1) {
            return redirect()->route('trainee.show', ["trainee" => $id])->with('successEdit', 'Employee updated successfully.');
        }
    }
    public function AssignSchedule(Request $request, string $id)
    {
        $employee = Employee::findOrFail($id);
        $employee->schedule_id = $request->input("id");
        $employee->update();
        if ($employee->training == 0) {
            return redirect()->route('employee.show', ["employee" => $id])->with('successEdit', 'Employee updated successfully.');
        } elseif ($employee->training == 1) {
            return redirect()->route('trainee.show', ["trainee" => $id])->with('successEdit', 'Employee updated successfully.');
        }
    }

    public function uploadCv(Request $request, $id)
    {
        $request->validate([
            'cvFile' => 'required|file|mimes:pdf|max:2048', // 2MB max file size and only PDF
        ]);

        $employee = Employee::findOrFail($id);

        // Handle File Upload
        if ($request->hasFile('cvFile')) {
            // Delete old CV if it exists
            if ($employee->cv && Storage::disk('public')->exists($employee->cv)) {
                Storage::disk('public')->delete($employee->cv);
            }

            // Store new file
            $path = $request->file('cvFile')->store('cv', 'public');
            $employee->cv = $path;
            $employee->save();

            return back()->with('success', 'CV uploaded successfully.');
        }

        return back()->with('error', 'There was an issue uploading the CV.');
    }
    public function uploadImage(Request $request, $id)
    {
        // Validate the uploaded file
        $request->validate([
            'image' => 'required|image', // 2MB max file size and only image files
        ]);

        // Retrieve the employee by ID
        $employee = Employee::findOrFail($id);

        // Handle the image file upload
        if ($request->hasFile('image')) {
            // Delete the old image if it exists in storage
            if ($employee->image && Storage::disk('public')->exists($employee->image)) {
                Storage::disk('public')->delete($employee->image);
            }

            // Store the new image file
            $path = $request->file('image')->store('images', 'public');
            $employee->image = $path;
            $employee->save();

            // Redirect back with success message
            return back()->with('success', 'Image uploaded successfully.');
        }

        // Redirect back with error message if image wasn't uploaded
        return back()->with('error', 'There was an issue uploading the image.');
    }



    public function deleteCv($id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);

        if ($employee->cv && Storage::disk('public')->exists($employee->cv)) {
            Storage::disk('public')->delete($employee->cv);
            $employee->cv = null;
            $employee->save();
            return back();
        }
        return back();
    }
    public function deleteImage($id): RedirectResponse
    {
        $employee = Employee::findOrFail($id);
        if ($employee->image && Storage::disk('public')->exists($employee->image)) {
            Storage::disk('public')->delete($employee->image);
            $employee->image = null;
            $employee->save();
            return back()->with('success', 'Image deleted successfully.');
        }
        return back()->with('error', 'No image found to delete.');
    }

    public function home()
    {
        $totalEmployees = Employee::count();
        $employee = Employee::where('training', 0)->count();
        $inTraining = Employee::where('training', 1)->count();
        $trained = Employee::where('training', 2)->count();
        $terminated = Employee::onlyTrashed()->count();

        // Fetch detailed position stats
        $positions = Position::withCount(['all_employees', 'employees', 'trainees', 'trained'])->get();

        // Last added 3 employees
        $lastAddedEmployees = Employee::where("training", 0)->latest()->take(3)->get();

        // Top 3 oldest employees
        $oldestEmployees = Employee::where("training", 0)->orderBy('start_date')->take(3)->get();

        // Top 3 employees with the highest salaries
        $highestSalaryEmployees = Employee::where("training", 0)->orderBy('salary', 'desc')->take(3)->get();

        // Top 3 employees with the lowest salaries
        $lowestSalaryEmployees = Employee::where("training", 0)->orderBy('salary')->take(3)->get();

        // Calculate average age for each position
        $averageAges = Position::with(['employees' => function ($query) {
            $query->selectRaw('position_id, AVG(TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE())) as average_age')
                  ->groupBy('position_id');
        }])->get()->mapWithKeys(function ($position) {
            return [$position->name => $position->employees->first()->average_age ?? 0];
        });

        return view('private.home', compact(
            'totalEmployees', 'employee', 'inTraining', 'trained', 'terminated', 'positions',
            'lastAddedEmployees', 'oldestEmployees', 'highestSalaryEmployees', 'lowestSalaryEmployees',
            'averageAges'));}

}