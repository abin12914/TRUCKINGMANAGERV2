<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty(old($selectName)) ? 'selected' : '' }}>Select material</option>
    @if(!empty($materialsCombo) && (count($materialsCombo) > 0))
        @foreach($materialsCombo as $material)
            <option value="{{ $material->id }}" {{ (old($selectName, $selectedMaterialId) == $material->id) ? 'selected' : '' }}>
                {{ $material->name. " / ". $material->alternate_name }}
            </option>
        @endforeach
    @endif
</select>
