@extends('layout.dashboard')

@section('content')
<div class="container">
    <h1>الإشعارات</h1>

    <!-- فلترة حسب السنة والموظف -->
    <form action="{{ route('notifications.index') }}" method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <label for="year">السنة</label>
                <input type="number" name="year" id="year" class="form-control"
                    value="{{ request('year', date('Y')) }}">
            </div>

            <div class="col-md-6">
                <label for="employee_id">الموظف</label>
                <select name="employee_id" id="employee_id" class="form-control">
                    <option value="">-- جميع الموظفين --</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}" {{ request('employee_id') == $employee->id ? 'selected' : '' }}>
                            {{ $employee->NOM_ET_PRENOM }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">فلترة</button>
            </div>
        </div>
    </form>

    <hr>

    <!-- عرض الإشعارات -->
    @if($notifications->count())
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>الموظف</th>
                    <th>الدرجة القديمة</th>
                    <th>الدرجة الجديدة</th>
                    <th>الإشعار</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notifications as $notification)
                    <tr>
                        <td>{{ $notification->employee->NOM_ET_PRENOM ?? '-' }}</td>
                        <td>{{ $notification->ancien_grade }} + {{ $notification->ancien_echelon }}</td>
                        <td>{{ $notification->nouveau_grade }} + {{ $notification->nouveau_echelon }}</td>
                        <td>{{ $notification->message }}</td>
                        <td>{{ \Carbon\Carbon::parse($notification->date_changement)->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-warning text-center">
            لا توجد إشعارات مطابقة للمعايير المحددة.
        </div>
    @endif

    <!-- روابط التنقل بين الصفحات -->
    @if ($notifications instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-3">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
