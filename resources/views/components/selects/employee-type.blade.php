<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select primary relation type</option>
    @if(!empty($employeeTypes) && (count($employeeTypes) > 0))
        @foreach($employeeTypes as $key => $type)
            <option value="{{ $key }}" {{ (old($selectName) == $key || $selectedType == $key) ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    @endif
</select>