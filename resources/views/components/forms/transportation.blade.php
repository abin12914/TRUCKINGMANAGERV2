<div class="form-group">
    <div class="col-md-6">
        <label for="truck_id" class="control-label"><b style="color: red;">* </b> Truck : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.trucks', ['selectedTruckId' => old('truck_id'), 'selectName' => 'truck_id', 'tabindex' => 1])
        @endcomponent
        @if(!empty($errors->first('truck_id')))
            <p style="color: red;" >{{$errors->first('truck_id')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="transportation_date" class="control-label"><b style="color: red;">* </b> Date : </label>
        <input type="text" class="form-control decimal_number_only datepicker_reg" name="transportation_date" id="transportation_date" placeholder="Transportation date" value="{{ old('transportation_date') }}" tabindex="2">
        @if(!empty($errors->first('transportation_date')))
            <p style="color: red;" >{{$errors->first('transportation_date')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="source_id" class="control-label"><b style="color: red;">* </b> Source : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.sites', ['selectedSiteId' => old('source_id'), 'selectName' => 'source_id', 'tabindex' => 3])
        @endcomponent
        @if(!empty($errors->first('source_id')))
            <p style="color: red;" >{{$errors->first('source_id')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="destination_id" class="control-label"><b style="color: red;">* </b> Destination : </label>
        {{-- adding trucks select component --}}
        @component('components.selects.sites', ['selectedSiteId' => old('destination_id'), 'selectName' => 'destination_id', 'tabindex' => 3])
        @endcomponent
        @if(!empty($errors->first('destination_id')))
            <p style="color: red;" >{{$errors->first('destination_id')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="contractor_account_id" class="control-label"><b style="color: red;">* </b> Contractor : </label>
        {{-- adding account select component --}}
        @component('components.selects.accounts', ['selectedAccountId' => old('contractor_account_id'), 'cashAccountFlag' => true, 'selectName' => 'contractor_account_id', 'activeFlag' => false, 'tabindex' => 5])
        @endcomponent
        @if(!empty($errors->first('contractor_account_id')))
            <p style="color: red;" >{{$errors->first('contractor_account_id')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="material_id" class="control-label"><b style="color: red;">* </b> Material : </label>
        {{-- adding materials select component --}}
        @component('components.selects.materials', ['selectedMaterialId' => old('material_id'), 'selectName' => 'material_id', 'activeFlag' => false, 'tabindex' => 9])
        @endcomponent
        @if(!empty($errors->first('material_id')))
            <p style="color: red;" >{{$errors->first('material_id')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="rent_type" class="control-label"><b style="color: red;">* </b> Rent Type : </label>
        {{-- adding rent type select component --}}
        @component('components.selects.rent-type', ['selectedRentTypeId' => old('rent_type'), 'selectName' => 'rent_type', 'activeFlag' => false, 'tabindex' => 10])
        @endcomponent
        @if(!empty($errors->first('rent_type')))
            <p style="color: red;" >{{$errors->first('rent_type')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="rent_measurement" class="control-label"><b style="color: red;">* </b> Measurement/Quantity : </label>
        <input type="text" class="form-control decimal_number_only" name="rent_measurement" id="rent_measurement" placeholder="Measurement" value="{{ old('rent_type') == 3 ? 1 : old('rent_measurement') }}" {{ old('rent_type') == 3 ? "readonly" : "" }} tabindex="7">
        @if(!empty($errors->first('rent_measurement')))
            <p style="color: red;" >{{$errors->first('rent_measurement')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="rent_rate" class="control-label"><b style="color: red;">* </b> Rent Rate : </label>
        <input type="text" class="form-control decimal_number_only" name="rent_rate" id="rent_rate" placeholder="Rent rate" value="{{ old('rent_rate') }}" tabindex="8">
        @if(!empty($errors->first('rent_rate')))
            <p style="color: red;" >{{$errors->first('rent_rate')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="trip_rent" class="control-label"><b style="color: red;">* </b> Trip Rent : </label>
        <input type="text" class="form-control decimal_number_only" name="trip_rent" id="trip_rent" placeholder="Trip rent" value="{{ old('trip_rent') }}" readonly tabindex="30">
        @if(!empty($errors->first('trip_rent')))
            <p style="color: red;" >{{$errors->first('trip_rent')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="no_of_trip" class="control-label"><b style="color: red;">* </b> No Of Transportations : </label>
        <input type="text" class="form-control decimal_number_only" name="no_of_trip" id="no_of_trip" placeholder="No of transportations" value="{{ old('no_of_trip') }}" tabindex="12">
        @if(!empty($errors->first('no_of_trip')))
            <p style="color: red;" >{{$errors->first('no_of_trip')}}</p>
        @endif
    </div>
    <div class="col-md-6">
        <label for="total_rent" class="control-label"><b style="color: red;">* </b> Total Rent : </label>
        <input type="text" class="form-control decimal_number_only" name="total_rent" id="total_rent" placeholder="Total rent" value="{{ old('total_rent') }}" readonly tabindex="30">
        @if(!empty($errors->first('total_rent')))
            <p style="color: red;" >{{$errors->first('total_rent')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="driver_id" class="control-label"><b style="color: red;">* </b> Driver : </label>
        {{-- adding employee select component --}}
        @component('components.selects.employees', ['selectedEmployeeId' => old('driver_id'), 'selectName' => 'driver_id', 'activeFlag' => false, 'tabindex' => 10])
        @endcomponent
        @if(!empty($errors->first('driver_id')))
            <p style="color: red;" >{{$errors->first('driver_id')}}</p>
        @endif
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_wage" class="control-label"><b style="color: red;">* </b> Trip Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_wage" id="driver_wage" placeholder="Driver trip wage" value="{{ old('driver_wage') }}" tabindex="11">
        @if(!empty($errors->first('driver_wage')))
            <p style="color: red;" >{{$errors->first('driver_wage')}}</p>
        @endif
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="driver_total_wage" class="control-label"><b style="color: red;">* </b> Total Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="driver_total_wage" id="driver_total_wage" placeholder="Driver trip wage" value="{{ old('driver_total_wage') }}" tabindex="11" readonly>
        @if(!empty($errors->first('driver_total_wage')))
            <p style="color: red;" >{{$errors->first('driver_total_wage')}}</p>
        @endif
    </div>
</div>
<div class="form-group">
    <div class="col-md-6">
        <label for="second_driver_id" class="control-label"><b style="color: red;">* </b> Assistant Driver : </label>
        {{-- adding employee select component --}}
        @component('components.selects.employees', ['selectedEmployeeId' => old('second_driver_id'), 'selectName' => 'second_driver_id', 'activeFlag' => false, 'tabindex' => 10])
        @endcomponent
        @if(!empty($errors->first('second_driver_id')))
            <p style="color: red;" >{{$errors->first('second_driver_id')}}</p>
        @endif
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="second_driver_wage" class="control-label"><b style="color: red;">* </b> Trip Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="second_driver_wage" id="second_driver_wage" placeholder="Assistant driver trip wage" value="{{ old('second_driver_wage') }}" tabindex="13">
        @if(!empty($errors->first('second_driver_wage')))
            <p style="color: red;" >{{$errors->first('second_driver_wage')}}</p>
        @endif
    </div>
    <div class="col-md-3 col-sm-6 col-xs-6">
        <label for="second_driver_total_wage" class="control-label"><b style="color: red;">* </b> Total Wage : </label>
        <input type="text" class="form-control decimal_number_only" name="second_driver_total_wage" id="second_driver_total_wage" placeholder="Assistant driver trip wage" value="{{ old('second_driver_total_wage') }}" tabindex="13" readonly>
        @if(!empty($errors->first('second_driver_total_wage')))
            <p style="color: red;" >{{$errors->first('second_driver_total_wage')}}</p>
        @endif
    </div>
</div>
