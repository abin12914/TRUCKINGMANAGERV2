<select class="form-control select2" name="{{ $selectName }}" id="{{ $selectName }}" tabindex="{{ $tabindex }}" style="width: 100%;">
    <option value="" {{ empty($selectedMaterialId) ? 'selected' : '' }}>Select material</option>
    @if(!empty($materialsCombo) && (count($materialsCombo) > 0))
        @foreach($materialsCombo as $material)
            <option value="{{ $material->id }}" {{ $selectedMaterialId == $material->id ? 'selected' : '' }}>
                {{ $material->name. " / ". $material->alternate_name }}
            </option>
        @endforeach
    @endif
</select>
