<div class="mx-auto bg-white rounded-lg shadow-lg p-8">
    <h2 class="text-2xl font-bold mb-6 text-gray-800" id="createRegistrationTypes">Registration Types</h2>
    <form action="{{ route('registrationTypes.store') }}" method="post" id="formRegistrationTypes">
        @csrf
        @method('POST')
        @include('registration_types.fields')    
        <div class="flex justify-end mt-6">
            <button class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-6 rounded transition-all cursor-pointer me-2 hidden" type="button"
            id="cancelRegistrationTypesButton" onclick="cancelEdit()">
                Cancel
            </button>

            <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded transition-all cursor-pointer" type="submit"
            id="createRegistrationTypesButton">
                Save
            </button>
        </div>
    </form>
</div>