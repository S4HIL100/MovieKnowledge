<x-layout>
    <div class="register-page flex-grow-1 d-flex align-items-center justify-content-center">
        <form class="card register-card bg-dark text-light" method="POST" action="/register">
            <h1 class="register-title">REGISTRATION</h1>
            @csrf
            <div class="card-body register-card-body">
                <div class="register-field">
                    <label for="exampleInputUsername1" class="form-label">Username</label>
                    <input type="text" name="username" class="form-control form-control-lg" id="exampleInputUsername1" required>
                    @error('username')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="register-field">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="register-field">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary py-2">Register</button>
            </div>
        </form>
    </div>
</x-layout>