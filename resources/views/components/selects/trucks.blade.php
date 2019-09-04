<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="">Select truck</option>
    @if(!empty($truckCombo) && (count($truckCombo) > 0))
        @foreach($truckCombo as $employee)
            <option value="{{ $employee->id }}" {{ (old($selectName) == $employee->id || $selectedEmployeeId == $employee->id) ? 'selected' : '' }}>{{ $employee->account->name }}</option>
        @endforeach
    @endif
</select>