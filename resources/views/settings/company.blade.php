<x-app-layout>
    @section('title', 'Company Settings')

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-3xl font-serif text-foreground">Settings</h1>
            <p class="text-muted-foreground mt-1">Manage your organization's configuration and preferences.</p>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- Settings Navigation -->
        <div class="bg-white rounded-lg shadow-sm">
            @include('settings.partials.tabs', ['activeTab' => $activeTab])

            <div class="p-6">
                <form action="{{ route('settings.company.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="space-y-8">
                        <!-- Company Identity -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Company Identity</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $settings['name'] ?? '') }}" required
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="legal_name" class="block text-sm font-medium text-gray-700 mb-1">Legal Name</label>
                                    <input type="text" name="legal_name" id="legal_name" value="{{ old('legal_name', $settings['legal_name'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="registration_number" class="block text-sm font-medium text-gray-700 mb-1">Registration Number</label>
                                    <input type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', $settings['registration_number'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="fiscal_year_start" class="block text-sm font-medium text-gray-700 mb-1">Fiscal Year Start</label>
                                    <select name="fiscal_year_start" id="fiscal_year_start"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="01-01" {{ ($settings['fiscal_year_start'] ?? '') === '01-01' ? 'selected' : '' }}>January 1</option>
                                        <option value="04-01" {{ ($settings['fiscal_year_start'] ?? '') === '04-01' ? 'selected' : '' }}>April 1</option>
                                        <option value="07-01" {{ ($settings['fiscal_year_start'] ?? '') === '07-01' ? 'selected' : '' }}>July 1</option>
                                        <option value="10-01" {{ ($settings['fiscal_year_start'] ?? '') === '10-01' ? 'selected' : '' }}>October 1</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Jamaica Tax Registration -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Jamaica Tax Registration</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="tax_registration_number" class="block text-sm font-medium text-gray-700 mb-1">Tax Registration Number (TRN)</label>
                                    <input type="text" name="tax_registration_number" id="tax_registration_number" value="{{ old('tax_registration_number', $settings['tax_registration_number'] ?? '') }}"
                                           placeholder="XXX-XXX-XXX"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="nis_number" class="block text-sm font-medium text-gray-700 mb-1">NIS Employer Number</label>
                                    <input type="text" name="nis_number" id="nis_number" value="{{ old('nis_number', $settings['nis_number'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="nht_number" class="block text-sm font-medium text-gray-700 mb-1">NHT Employer Number</label>
                                    <input type="text" name="nht_number" id="nht_number" value="{{ old('nht_number', $settings['nht_number'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Contact Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-1">Address Line 1</label>
                                    <input type="text" name="address_line_1" id="address_line_1" value="{{ old('address_line_1', $settings['address_line_1'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                                    <input type="text" name="address_line_2" id="address_line_2" value="{{ old('address_line_2', $settings['address_line_2'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">City</label>
                                    <input type="text" name="city" id="city" value="{{ old('city', $settings['city'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="parish" class="block text-sm font-medium text-gray-700 mb-1">Parish</label>
                                    <select name="parish" id="parish"
                                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                        <option value="">Select Parish</option>
                                        @foreach($parishes as $parish)
                                        <option value="{{ $parish }}" {{ ($settings['parish'] ?? '') === $parish ? 'selected' : '' }}>{{ $parish }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="country" class="block text-sm font-medium text-gray-700 mb-1">Country</label>
                                    <input type="text" name="country" id="country" value="{{ old('country', $settings['country'] ?? 'Jamaica') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone', $settings['phone'] ?? '') }}"
                                           placeholder="+1 (876) XXX-XXXX"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $settings['email'] ?? '') }}"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700 mb-1">Website</label>
                                    <input type="url" name="website" id="website" value="{{ old('website', $settings['website'] ?? '') }}"
                                           placeholder="https://www.example.com"
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                            </div>
                        </div>

                        <!-- Company Logo -->
                        <div>
                            <h3 class="text-lg font-semibold text-foreground mb-4">Company Logo</h3>
                            <div class="flex items-start gap-6">
                                @if(!empty($settings['logo']))
                                <div class="flex-shrink-0">
                                    <img src="{{ Storage::url($settings['logo']) }}" alt="Company Logo" class="h-24 w-24 object-contain rounded-lg border border-gray-200">
                                </div>
                                @endif
                                <div class="flex-1">
                                    <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">Upload New Logo</label>
                                    <input type="file" name="logo" id="logo" accept="image/*"
                                           class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-primary file:text-white hover:file:bg-primary/90">
                                    <p class="text-xs text-muted-foreground mt-1">Recommended: 200x200px, PNG or SVG format</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-200 flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-primary text-white rounded-md hover:bg-primary/90 transition-colors">
                            Save Company Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
