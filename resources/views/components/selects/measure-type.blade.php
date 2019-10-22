<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select measurement type</option>
    @if(!empty($measureTypes) && (count($measureTypes) > 0))
        @foreach($measureTypes as $key => $type)
            <option value="{{ $key }}" {{ $selectedMeasureTypeId == $key ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    @endif
</select>
