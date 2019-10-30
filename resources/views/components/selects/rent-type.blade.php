<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty($selectedRentTypeId) ? 'selected' : '' }}>Select rent type</option>
    @if(!empty($rentTypes) && (count($rentTypes) > 0))
        @foreach($rentTypes as $key => $rentType)
            <option value="{{ $key }}" {{ $selectedRentTypeId == $key ? 'selected' : '' }}>
                {{ $rentType }}
            </option>
        @endforeach
    @endif
</select>
