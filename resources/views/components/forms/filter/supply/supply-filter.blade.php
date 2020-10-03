<div class="row">
    <div class="col-md-1"></div>
    <div class="col-md-10">
        <div class="form-group">
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('from_date')) ? 'has-error' : '' }}">
                <label for="from_date" class="control-label">From Date : </label>
                <input type="text" class="form-control datepicker" name="from_date" id="from_date" value="{{ old('from_date', $params['from_date']['paramValue']) }}" tabindex="1">
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'from_date'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('to_date')) ? 'has-error' : '' }}">
                <label for="to_date" class="control-label">To Date : </label>
                <input type="text" class="form-control datepicker" name="to_date" id="to_date" value="{{ old('to_date', $params['to_date']['paramValue']) }}" tabindex="2">
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'to_date'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('contractor_account_id')) ? 'has-error' : '' }}">
                <label for="contractor_account_id" class="control-label">Contractor : </label>
                {{-- adding account select component --}}
                @component('components.selects.accounts', ['selectedAccountId' => old('contractor_account_id', $params['contractor_account_id']['paramValue']), 'cashAccountFlag' => true, 'selectName' => 'contractor_account_id', 'activeFlag' => false, 'tabindex' => 3])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'contractor_account_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('truck_id')) ? 'has-error' : '' }}">
                <label for="truck_id" class="control-label">Truck : </label>
                {{-- adding trucks select component --}}
                @component('components.selects.trucks', ['selectedTruckId' => old('truck_id', $params['truck_id']['paramValue']), 'selectName' => 'truck_id', 'tabindex' => 4])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'truck_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('source_id')) ? 'has-error' : '' }}">
                <label for="source_id" class="control-label">Source : </label>
                {{-- adding trucks select component --}}
                @component('components.selects.sites', ['selectedSiteId' => old('source_id', $params['source_id']['paramValue']), 'selectName' => 'source_id', 'tabindex' => 5])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'source_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('destination_id')) ? 'has-error' : '' }}">
                <label for="destination_id" class="control-label">Destination : </label>
                {{-- adding trucks select component --}}
                @component('components.selects.sites', ['selectedSiteId' => old('destination_id', $params['destination_id']['paramValue']), 'selectName' => 'destination_id', 'tabindex' => 6])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'destination_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('driver_id')) ? 'has-error' : '' }}">
                <label for="driver_id" class="control-label">Driver : </label>
                {{-- adding employee select component --}}
                @component('components.selects.employees', ['selectedEmployeeId' => old('driver_id', $params['driver_id']['paramValue']), 'selectName' => 'driver_id', 'activeFlag' => false, 'tabindex' => 7])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'driver_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('material_id')) ? 'has-error' : '' }}">
                <label for="material_id" class="control-label">Material : </label>
                {{-- adding materials select component --}}
                @component('components.selects.materials', ['selectedMaterialId' => old('material_id', $params['material_id']['paramValue']), 'selectName' => 'material_id', 'activeFlag' => false, 'tabindex' => 8])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'material_id'])
                @endcomponent
            </div>
            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-6 {{ !empty($errors->first('no_of_records')) ? 'has-error' : '' }}">
                <label for="no_of_records" class="control-label">No Of Records Per Page : </label>
                {{-- adding no of records text component --}}
                @component('components.texts.no-of-records-text', ['noOfRecords' => $noOfRecords, 'tabindex' => 9])
                @endcomponent
                {{-- adding error_message p tag component --}}
                @component('components.paragraph.error_message', ['fieldName' => 'no_of_records'])
                @endcomponent
            </div>
        </div>
    </div>
</div>
<div class="clearfix"></div><br>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-2 col-xs-2"></div>
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
        <button type="reset" class="btn btn-default btn-block btn-flat"  value="reset" tabindex="11">Clear</button>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-4">
        <button type="submit" class="btn btn-primary btn-block btn-flat submit-button" tabindex=10><i class="fa fa-search"></i> Search</button>
    </div>
</div>
