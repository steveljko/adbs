<div id="contnet">
    <h1 class="text-2xl text-center">Database</h1>
    <form hx-post="{{ route('installer.database.setup') }}" hx-swap="none" class="space-y-6">
        @csrf

        <div class="grid md:grid-cols-2 gap-6">
            <x-form.input name="db_host" label="Database Host" type="text" value="localhost"></x-form.input>
            <x-form.input name="db_port" label="Database Port" type="number" value="5432"></x-form.input>
        </div>

        <x-form.input name="db_database" label="Database Name" type="text"></x-form.input>

        <div class="grid md:grid-cols-2 gap-6">
            <x-form.input name="db_username" label="Database Username" type="text"></x-form.input>
            <x-form.input name="db_password" label="Database Password" type="password"></x-form.input>
        </div>

        <div class="flex justify-between">
            <x-button type="submit">Test & Save Configuration</x-button>
        </div>
    </form>
</div>
