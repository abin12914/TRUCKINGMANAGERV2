<div class="form-group">
    <div class="col-md-6">
        <label for="truck_id" class="control-label"><b style="color: red;">* </b> Truck : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', (!empty($transportation) ? $transportation->truck_id : '' )), 'selectName' => 'truck_id', 'tabindex' => 1])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="transportation_date" class="control-label"><b style="color: red;">* </b> Date : </label>
        <input type="text" class="form-control decimal_number_only datepicker_reg" name="transportation_date" id="transportation_date" placeholder="Transportation date" value="{{ old('transportation_date', (!empty($transportation) ? $transportation->transaction->transaction_date->format('d-m-Y') : '' )) }}" tabindex="2">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'transportation_date'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="source_id" class="control-label"><b style="color: red;">* </b> Source : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.sites', ['selectedSiteId' => old('source_id', (!empty($transportation) ? $transportation->source_id : '')), 'selectName' => 'source_id', 'tabindex' => 3])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'source_id'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="destination_id" class="control-label"><b style="color: red;">* </b> Destination : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.sites', ['selectedSiteId' => old('destination_id', (!empty($transportation) ? $transportation->destination_id : '')), 'selectName' => 'destination_id', 'tabindex' => 4])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'destination_id'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="contractor_account_id" class="control-label"><b style="color: red;">* </b> Contractor : </label>
        {{-- adding account select component --}}
        @component('components.selects.accounts', ['selectedAccountId' => old('contractor_account_id', (!empty($transportation) ? $transportation->transaction->debit_account_id : '')), 'cashAccountFlag' => true, 'selectName' => 'contractor_account_id', 'activeFlag' => false, 'tabindex' => 5])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'contractor_account_id'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="material_id" class="control-label"><b style="color: red;">* </b> Material : </label>
        {{-- adding materials select component --}}
        @component('components.selects.materials', ['selectedMaterialId' => old('material_id', (!empty($transportation) ? $transportation->material_id : '')), 'selectName' => 'material_id', 'activeFlag' => false, 'tabindex' => 6])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'material_id'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="rent_type" class="control-label"><b style="color: red;">* </b> Rent Type : </label>
        {{-- adding rent type select component --}}
        @component('components.selects.rent-type', ['selectedRentTypeId' => old('rent_type', (!empty($transportation) ? $transportation->rent_type : '')), 'selectName' => 'rent_type', 'activeFlag' => false, 'tabindex' => 7])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'rent_type'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="rent_measurement" class="control-label"><b style="color: red;">* </b> Measurement/Quantity : </label>
        <input type="text" class="form-control decimal_number_only" name="rent_measurement" id="rent_measurement" placeholder="Measurement"
            value="{{ old('rent_type', (!empty($transportation) ? $transportation->rent_type : '')) == 3 ? 1 : (old('rent_measurement', (!empty($transportation) ? $transportation->measurement : '' ))) }}"
            {{ old('rent_type', (!empty($transportation) ? $transportation->rent_type : '')) == 3 ? 'readonly' : '' }} tabindex="8">
            {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'rent_measurement'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="rent_rate" class="control-label"><b style="color: red;">* </b> Rent Rate : </label>
        <input type="text" class="form-control decimal_number_only" name="rent_rate" id="rent_rate" placeholder="Rent rate" value="{{ old('rent_rate', (!empty($transportation) ? $transportation->rent_rate : '')) }}" tabindex="9">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'rent_rate'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="trip_rent" class="control-label"><b style="color: red;">* </b> Trip Rent : </label>
        <input type="text" class="form-control decimal_number_only" name="trip_rent" id="trip_rent" placeholder="Trip rent" value="{{ old('trip_rent', (!empty($transportation) ? $transportation->trip_rent : '')) }}" readonly tabindex="-1">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'trip_rent'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="no_of_trip" class="control-label"><b style="color: red;">* </b> No Of Transportations : </label>
        <input type="text" class="form-control decimal_number_only" name="no_of_trip" id="no_of_trip" placeholder="No of transportations" value="{{ old('no_of_trip', (!empty($transportation) ? $transportation->no_of_trip : '')) }}" tabindex="10">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'no_of_trip'])
        @endcomponent
    </div>
    <div class="col-md-6">
        <label for="total_rent" class="control-label"><b style="color: red;">* </b> Total Rent : </label>
        <input type="text" class="form-control decimal_number_only" name="total_rent" id="total_rent" placeholder="Total rent" value="{{ old('total_rent', (!empty($transportation) ? $transportation->total_rent : '')) }}" readonly tabindex="-1">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'total_rent'])
        @endcomponent
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="driver_id" class="control-label"><b style="color: red;">* </b> Driver : </label>
        {{-- adding employee select component --}}
        @component('components.selects.employees', ['selectedEmployeeId' => old('driver_id', (!empty($transportation) ? $transportation->employeeWages->first()->employee_id : '')), 'selectName' => 'driver_id', 'activeFlag' => false, 'tabindex' => 11])
        @endcomponent
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_id'])
        @endcomponent
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_wage" class="control-label"><b style="color: red;">* </b> Trip Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_wage" id="driver_wage" placeholder="Driver trip wage" value="{{ old('driver_wage', (!empty($transportation) ? $transportation->employeeWages->first()->wage_amount : '')) }}" tabindex="12">
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_wage'])
        @endcomponent
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_total_wage" class="control-label"><b style="color: red;">* </b> Total Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_total_wage" id="driver_total_wage" placeholder="Driver trip wage" value="{{ old('driver_total_wage', (!empty($transportation) ? $transportation->employeeWages->first()->total_wage_amount : '')) }}" tabindex="-1" readonly>
        {{-- adding error_message p tag component --}}
        @component('components.paragraph.error_message', ['fieldName' => 'driver_total_wage'])
        @endcomponent
    </div>
</div>
<input type="hidden" name="truck_reg_number" id="truck_reg_number" value="" />
<input type="hidden" name="source_name" id="source_name" value="" />
<input type="hidden" name="destination_name" id="destination_name" value="" />
<input type="hidden" name="material_name" id="material_name" value="" />
