<div class="space-y-6" id="fcontent">
    <div class="grid md:grid-cols-2 gap-6">
        <x-form.input name="db_host" label="Database Host" type="text" value="localhost"></x-form.input>
        <x-form.input name="db_port" label="Database Port" type="number" value="3306"></x-form.input>
    </div>

    <x-form.input name="db_database" label="Database Name" type="text"></x-form.input>

    <div class="grid md:grid-cols-2 gap-6">
        <x-form.input name="db_username" label="Database Username" type="text"></x-form.input>
        <x-form.input name="db_password" label="Database Password" type="password"></x-form.input>
    </div>
</div>
<x-button type="submit" id="submit" hx-swap-oob="outerHTML">Test & Save Configuration</x-button>
