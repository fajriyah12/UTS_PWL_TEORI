@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10" x-data="checkoutForm()">
    
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Checkout Tiket</h1>
        <p class="text-slate-500">Lengkapi data diri dan pengunjung untuk melanjutkan pembayaran.</p>
    </div>

    <form method="POST" action="{{ route('checkout.store', $ticket->id) }}">
        @csrf

        <!-- Detail Pembeli -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                <h2 class="font-semibold text-slate-800 flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Detail Pembeli
                </h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" x-model="buyer.name" required 
                               class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                        <input type="email" name="email" x-model="buyer.email" required 
                               class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            E-Tiket akan dikirim ke email ini
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Tiket & Jumlah -->
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
            <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <p class="text-xs font-semibold tracking-wide text-indigo-600 uppercase mb-1">Kategori Tiket</p>
                    <h3 class="text-lg font-bold text-slate-900">{{ $ticket->event->title }}</h3>
                    <p class="text-slate-600">{{ $ticket->name }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-slate-700">Jumlah Tiket:</label>
                    <input type="number" name="quantity" x-model.number="quantity" min="1" max="{{ $ticket->per_user_limit }}" 
                           @change="updateVisitors()"
                           class="w-20 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-center font-bold">
                </div>
            </div>
        </div>

        <!-- Detail Pengunjung (Dynamic) -->
        <template x-for="(visitor, index) in visitors" :key="index">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden mb-6">
                <div class="px-6 py-4 bg-slate-50 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-semibold text-slate-800 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                        </svg>
                        Pengunjung <span x-text="index + 1"></span>
                    </h2>
                    
                    <!-- Toggle Same as Buyer (Only for Visitor 1) -->
                    <div x-show="index === 0" class="flex items-center gap-2">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="sameAsBuyer" @change="syncBuyerData()" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600"></div>
                            <span class="ml-2 text-sm font-medium text-slate-600">Sama dengan pembeli</span>
                        </label>
                    </div>
                </div>

                <div class="p-6 space-y-4">
                    <!-- Nama & Email -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nama Lengkap</label>
                            <input type="text" :name="'visitors['+index+'][name]'" x-model="visitor.name" required 
                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Email</label>
                            <input type="email" :name="'visitors['+index+'][email]'" x-model="visitor.email" required 
                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- DOB & Gender -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                            <input type="date" :name="'visitors['+index+'][dob]'" x-model="visitor.dob" required 
                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Jenis Kelamin</label>
                            <div class="flex gap-4 mt-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" :name="'visitors['+index+'][gender]'" value="male" x-model="visitor.gender" class="form-radio text-indigo-600">
                                    <span class="ml-2 text-sm text-slate-700">Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" :name="'visitors['+index+'][gender]'" value="female" x-model="visitor.gender" class="form-radio text-pink-600">
                                    <span class="ml-2 text-sm text-slate-700">Perempuan</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Phone & Institution -->
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Nomor Telepon</label>
                            <input type="tel" :name="'visitors['+index+'][phone]'" x-model="visitor.phone" required placeholder="08..."
                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Institusi / Perusahaan</label>
                            <input type="text" :name="'visitors['+index+'][institution]'" x-model="visitor.institution" required 
                                   class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pekerjaan</label>
                        <input type="text" :name="'visitors['+index+'][occupation]'" x-model="visitor.occupation" required 
                               class="w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
        </template>

        <!-- Total & Submit -->
        <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 p-4 shadow-lg md:static md:bg-transparent md:border-0 md:shadow-none md:p-0">
            <div class="max-w-3xl mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="text-center md:text-left">
                    <p class="text-sm text-slate-500">Total Pembayaran</p>
                    <p class="text-2xl font-bold text-indigo-600">
                        Rp <span x-text="(quantity * {{ $ticket->price }}).toLocaleString('id-ID')"></span>
                    </p>
                </div>
                <button type="submit" class="w-full md:w-auto px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold shadow-lg shadow-indigo-200 transition transform hover:-translate-y-0.5">
                    Lanjut Pembayaran
                </button>
            </div>
        </div>
        
        <!-- Spacer for fixed bottom on mobile -->
        <div class="h-24 md:hidden"></div>

    </form>
</div>

<script>
    function checkoutForm() {
        return {
            quantity: {{ $quantity }},
            buyer: { 
                name: '{{ Auth::user()->name }}', 
                email: '{{ Auth::user()->email }}',
                dob: '{{ Auth::user()->date_of_birth }}',
                gender: '{{ Auth::user()->gender }}',
                phone: '{{ Auth::user()->phone }}',
                institution: '{{ Auth::user()->institution }}',
                occupation: '{{ Auth::user()->occupation }}'
            },
            sameAsBuyer: false,
            visitors: [
                { name: '', email: '', dob: '', gender: '', phone: '', institution: '', occupation: '' }
            ],
            
            updateVisitors() {
                // Ensure quantity is valid
                if (this.quantity < 1) this.quantity = 1;
                if (this.quantity > {{ $ticket->per_user_limit }}) this.quantity = {{ $ticket->per_user_limit }};

                // Adjust visitors array length
                while (this.visitors.length < this.quantity) {
                    this.visitors.push({ name: '', email: '', dob: '', gender: '', phone: '', institution: '', occupation: '' });
                }
                while (this.visitors.length > this.quantity) {
                    this.visitors.pop();
                }

                // Re-sync if toggle is on
                if (this.sameAsBuyer) {
                    this.syncBuyerData();
                }
            },

            syncBuyerData() {
                if (this.sameAsBuyer && this.visitors.length > 0) {
                    this.visitors[0].name = this.buyer.name;
                    this.visitors[0].email = this.buyer.email;
                    this.visitors[0].dob = this.buyer.dob;
                    this.visitors[0].gender = this.buyer.gender;
                    this.visitors[0].phone = this.buyer.phone;
                    this.visitors[0].institution = this.buyer.institution;
                    this.visitors[0].occupation = this.buyer.occupation;
                } else if (!this.sameAsBuyer && this.visitors.length > 0) {
                    // Optional: clear data when untoggled
                    // this.visitors[0].name = '';
                    // this.visitors[0].email = '';
                }
            }
        }
    }
</script>
@endsection
