<form action="{{ route('admin.graduates.update_jobs', [$item->id, $data_company->id]) }}" method="POST">
    @csrf @method('PATCH')

    <div class="form-group">
        <label class="form-label">Empresa:</label>
        <select name="company_id" class="form-control select2bs4">
            <option value="-1">Seleccione la empresa.</option>
            @forelse ($companies as $company)

            <option value="{{ $company->id }}" {{ isSelectedOld( $data_company->company->id, $company->id ) }}>
                {{$company->name}}
            </option>
            @empty
            @endforelse
        </select>
    </div>
    @error('company_id')
    <small class="text-danger">{{ $message }}</small>
    @enderror
  



    <div class="form-group">
        <label class="form-label">Salario (Pesos Colombianos - COP):</label>
        <input type="text" class="form-control salary-input" name="salary" value="{{ $data_company->salary }}" >
    </div>
    @error('salary')
        <small class="text-danger">{{ $message }}</small>
    @enderror

    


    <div class="form-group">
        <label class="form-label">¿Está actualmente trabajando? (SI O NO):</label>
        <select name="in_working" class="form-control select2bs4">
            <option value="-1" disabled selected>Seleccione SI o NO.</option>


            <option value="0" {{ isSelectedOld( $data_company->in_working, 0) }}>
                NO
            </option>

            <option value="1" {{ isSelectedOld( $data_company->in_working, 1) }}>
                SI
            </option>

        </select>
    </div>
    @error('in_working')
    <small class="text-danger">{{ $message }}</small>
    @enderror
  



    <!-- Submit -->
    <div class="mt-4">
        <div class="btn-group" role="group" aria-label="Basic example">
            <button type="submit" class="btn btn-danger">Guardar</button>
            <div class="ml-5">
                <a class="btn btn-warning " style="color:black;
                text-decoration: none;" href="{{ route('admin.graduates.show', $item->id) }}">Regresar</a>
            </div>
        </div>

    </div>
    <!-- ./Submit -->

</form>
