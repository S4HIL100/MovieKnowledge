<x-layout>
    <div class="register-page flex-grow-1 d-flex align-items-center justify-content-center">
        <form class="card register-card bg-dark text-light" method="POST" action="/register">
            <h1 class="register-title">REGISTRATION</h1>
            @csrf
            <div class="card-body register-card-body">
                <div class="register-field">
                    <label for="name" class="form-label">Username</label>
                    <input type="text" name="name" class="form-control form-control-lg" id="name" placeholder = "John" required>
                    @error('name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="register-field">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control form-control-lg" id="exampleInputEmail1" placeholder = "JohnDoe@gmail.com" required>
                    @error('email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="register-field">
                    <label for="exampleInputPassword1" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form-control-lg" id="exampleInputPassword1" placeholder = "........" required>
                    @error('password')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <button type="submit" class="btn btn-primary py-2">Register</button>
            </div>
        </form>
    </div>
</x-layout>