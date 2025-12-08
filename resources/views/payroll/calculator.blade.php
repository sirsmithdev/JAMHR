<x-app-layout>
    @section('title', 'Tax Calculator')

    <div class="space-y-8">
        <div>
            <h1 class="text-3xl font-serif text-foreground">Jamaican Payroll Tax Calculator</h1>
            <p class="text-muted-foreground mt-1">Estimate statutory deductions for any gross salary amount.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Calculator Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="font-serif font-semibold mb-4">Enter Gross Pay</h3>
                <div class="space-y-4">
                    <div class="space-y-2">
                        <label for="calc_gross" class="block text-sm font-medium text-foreground">Monthly Gross Pay (JMD)</label>
                        <input type="number" id="calc_gross" value="350000" step="1000" class="w-full px-3 py-2 border border-border rounded-md focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary text-lg font-bold">
                    </div>
                    <button type="button" onclick="calculateTax()" class="w-full px-4 py-2 rounded-md text-sm font-medium text-white bg-primary hover:bg-primary/90 transition-colors">
                        Calculate Deductions
                    </button>
                </div>

                <!-- Results -->
                <div id="results" class="mt-6 space-y-4">
                    <div class="border-t border-border pt-4">
                        <h4 class="font-medium text-sm text-muted-foreground mb-3">Employee Deductions</h4>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>NHT (2%)</span>
                                <span id="nht_emp" class="font-medium">JMD 7,000.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>NIS (3%)</span>
                                <span id="nis_emp" class="font-medium">JMD 10,500.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Ed Tax (2.25%)</span>
                                <span id="ed_emp" class="font-medium">JMD 7,875.00</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Income Tax (PAYE)</span>
                                <span id="paye" class="font-medium">JMD 48,156.25</span>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-border pt-4">
                        <div class="flex justify-between text-lg font-bold">
                            <span>Total Deductions</span>
                            <span id="total_ded" class="text-red-600">JMD 73,531.25</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold mt-2 text-emerald-600">
                            <span>Net Pay</span>
                            <span id="net_pay">JMD 276,468.75</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tax Rates Reference -->
            <div class="space-y-6">
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h3 class="font-serif font-semibold mb-4">2025 Jamaican Tax Rates</h3>
                    <div class="space-y-4">
                        @foreach(['nht' => 'National Housing Trust', 'nis' => 'National Insurance Scheme', 'ed_tax' => 'Education Tax', 'heart' => 'HEART Trust/NTA'] as $key => $name)
                        <div class="p-4 bg-muted/30 rounded-lg">
                            <div class="flex justify-between items-start mb-2">
                                <h4 class="font-semibold">{{ $name }}</h4>
                                <span class="text-xs px-2 py-1 bg-primary/10 text-primary rounded">Mandatory</span>
                            </div>
                            <div class="text-sm space-y-1">
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Employee:</span>
                                    <span class="font-medium">{{ $taxRates[$key]['employee'] }}%</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-muted-foreground">Employer:</span>
                                    <span class="font-medium">{{ $taxRates[$key]['employer'] }}%</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h4 class="font-semibold text-blue-800 mb-2">Income Tax Threshold</h4>
                    <p class="text-sm text-blue-700">
                        Annual threshold: JMD {{ number_format($taxRates['income_tax']['threshold_annual']) }}<br>
                        Monthly threshold: JMD {{ number_format($taxRates['income_tax']['threshold_annual'] / 12) }}<br>
                        Tax rate on income above threshold: {{ $taxRates['income_tax']['rate'] }}%
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function calculateTax() {
            const gross = parseFloat(document.getElementById('calc_gross').value) || 0;

            // Calculate deductions
            const nht = gross * 0.02;
            const nis = gross * 0.03;
            const edTax = gross * 0.0225;

            // Calculate taxable income and PAYE
            const monthlyThreshold = 125008;
            const taxableGross = gross - nht - nis - edTax;
            const taxableIncome = Math.max(0, taxableGross - monthlyThreshold);
            const paye = taxableIncome * 0.25;

            const totalDed = nht + nis + edTax + paye;
            const netPay = gross - totalDed;

            // Update display
            document.getElementById('nht_emp').textContent = 'JMD ' + nht.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('nis_emp').textContent = 'JMD ' + nis.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('ed_emp').textContent = 'JMD ' + edTax.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('paye').textContent = 'JMD ' + paye.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('total_ded').textContent = 'JMD ' + totalDed.toLocaleString('en-US', {minimumFractionDigits: 2});
            document.getElementById('net_pay').textContent = 'JMD ' + netPay.toLocaleString('en-US', {minimumFractionDigits: 2});
        }

        // Calculate on page load
        document.addEventListener('DOMContentLoaded', calculateTax);
    </script>
</x-app-layout>
