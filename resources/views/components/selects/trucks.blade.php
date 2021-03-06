<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" style="width: 100%" tabindex="{{ $tabindex }}">
    <option value="" {{ empty($selectedTruckId) ? 'selected' : '' }}>Select truck</option>
    @if(!empty($trucksCombo) && (count($trucksCombo) > 0))
        @foreach($trucksCombo as $truck)
            <option value="{{ $truck->id }}" {{ $selectedTruckId == $truck->id ? 'selected' : '' }}>
                {{ $truck->reg_number }}
            </option>
        @endforeach
    @endif
</select>
