<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select employee</option>
    @if(!empty($employeesCombo) && (count($employeesCombo) > 0))
        @foreach($employeesCombo as $employee)
            <option value="{{ $employee->id }}"  data-wage-type="{{ $employee->wage_type }}" data-wage-amount="{{ $employee->wage_value }}"  {{ (old($selectName) == $employee->id || $selectedEmployeeId == $employee->id) ? 'selected' : '' }}>{{ $employee->account->name }}</option>
        @endforeach
    @endif
</select>
