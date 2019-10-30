<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty($selectedType) ? 'selected' : '' }}>Select option</option>
    @if(!empty($wageTypes) && (count($wageTypes) > 0))
        @foreach($wageTypes as $key => $type)
            <option value="{{ $key }}" {{ $selectedType == $key ? 'selected' : '' }}>
                {{ $type }}
            </option>
        @endforeach
    @endif
</select>
