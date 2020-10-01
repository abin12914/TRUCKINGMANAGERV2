<div class="form-group">
    <div class="col-md-6">
        <label for="driver_id_{{ $driverIndex }}" class="control-label"><b style="color: red;">* </b> Driver [{{ $driverIndex + 1 }}]: </label>
        {{-- adding employee select component --}}
        @component('components.selects.employees', [
            'selectedEmployeeId' => old('driver_id['. $driverIndex. ']', ((!empty($transportation) && !empty($transportation->employeeWages[$driverIndex])) ? $transportation->employeeWages[$driverIndex]->employee_id : '')),
            'selectId' => 'driver_id_'. $driverIndex,
            'selectName' => 'driver_id['. $driverIndex. ']',
            'activeFlag' => false,
            'tabindex' => (11 + $driverIndex)
        ])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_id['. $driverIndex. ']'])
        @endcomponent
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_wage_{{ $driverIndex }}" class="control-label"><b style="color: red;">* </b> Trip Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_wage[{{ $driverIndex }}]"
            id="driver_wage_{{ $driverIndex }}" placeholder="Driver trip wage"
            value="{{ old('driver_wage['. $driverIndex. ']', ((!empty($transportation) && !empty($transportation->employeeWages[$driverIndex])) ? $transportation->employeeWages[$driverIndex]->wage_amount : '')) }}"
            tabindex="{{ (12 + $driverIndex + 1) }}">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_wage['. $driverIndex. ']'])
        @endcomponent
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_total_wage_{{ $driverIndex }}" class="control-label"><b style="color: red;">* </b> Total Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_total_wage[{{ $driverIndex }}]"
        id="driver_total_wage_{{ $driverIndex }}" placeholder="Driver trip wage"
        value="{{ old('driver_total_wage['. $driverIndex. ']', ((!empty($transportation) && !empty($transportation->employeeWages[$driverIndex])) ? $transportation->employeeWages[$driverIndex]->total_wage_amount : '')) }}"
        tabindex="-1" readonly>
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_total_wage['. $driverIndex. ']'])
        @endcomponent
    </div>
</div>
