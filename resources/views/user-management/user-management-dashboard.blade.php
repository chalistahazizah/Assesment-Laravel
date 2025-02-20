<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight">User Management</h2>
    </x-slot>

    <div class="flex justify-between items-center mb-6">
        @if(auth()->user()->hasRole('admin'))
            <button onclick="openUserModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                + Tambah User
            </button>
        @endif
    </div>

    <!-- Tampilkan Data User berdasarkan Role -->
    @foreach (['admins' => 'Admin', 'managers' => 'Manager', 'users' => 'User'] as $roleVar => $roleTitle)
        <div class="max-w-6xl mx-auto bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-6 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold text-gray-700 dark:text-white">Data {{ $roleTitle }}</h4>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full border bg-white dark:bg-gray-800 shadow-lg rounded-md">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2">Nama</th>
                            <th class="px-4 py-2">Email</th>
                            @if($roleVar === 'users') <th class="px-4 py-2">Manager</th> @endif
                            @if(auth()->user()->hasRole('admin')) <th class="px-4 py-2">Aksi</th> @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($$roleVar as $user)
                        <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-4 py-2">{{ $user->name }}</td>
                            <td class="px-4 py-2">{{ $user->email }}</td>
                            @if($roleVar === 'users')
                                <td class="px-4 py-2">{{ $user->manager ? $user->manager->name : '-' }}</td>
                            @endif
                            @if(auth()->user()->hasRole('admin'))
                            <td class="px-4 py-2">
                                <button onclick="openEditModal({{ $user->id }})" class="bg-yellow-500 text-white px-3 py-1 rounded-lg hover:bg-yellow-600">
                                    Edit
                                </button>
                                <form action="{{ route('user-management.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus user ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach

    <!-- Modal Tambah User -->
    <div id="userModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
        <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
            <h2 class="text-lg font-semibold mb-4">Tambah User</h2>
            <form id="addUserForm" action="{{ route('user-management.store') }}" method="POST">
                @csrf
                <input type="text" name="name" placeholder="Nama" required class="w-full px-4 py-2 mb-3 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                <input type="email" name="email" placeholder="Email" required class="w-full px-4 py-2 mb-3 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 mb-3 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                <select name="role" id="roleSelect" class="w-full px-4 py-2 mb-3 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
                <div id="managerField" class="mb-3 hidden">
                    <select name="manager_id" class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                        <option value="">Pilih Manager</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}">{{ $manager->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex justify-end">
                    <button type="button" onclick="closeUserModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">
                        Batal
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit User -->
<div id="editUserModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
        <h2 class="text-lg font-semibold mb-4">Edit User</h2>

        <form id="editUserForm">
            @csrf
            @method('PUT')

            <input type="hidden" id="editUserId">

            <!-- Nama -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Nama</label>
                <input type="text" id="editName" name="name" required class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 text-black dark:text-white">
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Email</label>
                <input type="email" id="editEmail" name="email" required class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 text-black dark:text-white">
            </div>

            <!-- Password (Opsional) -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Password (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" id="editPassword" name="password" class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 text-black dark:text-white">
            </div>

            <!-- Role -->
            <div class="mb-4">
                <label class="block text-sm font-medium">Role</label>
                <select id="editRole" name="role" class="w-full px-4 py-2 border rounded-md dark:bg-gray-700 text-black dark:text-white">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 mr-2">
                    Batal
                </button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>


</x-app-layout>

<script>
    function openUserModal() {
        document.getElementById('userModal').classList.remove('hidden');
    }
    function closeUserModal() {
        document.getElementById('userModal').classList.add('hidden');
    }

    document.getElementById('roleSelect').addEventListener('change', function () {
        let managerField = document.getElementById('managerField');
        managerField.classList.toggle('hidden', this.value !== 'user');
    });

    function openEditModal(userId) {
        fetch(`/user-management/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editName').value = data.name;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editRole').value = data.role;
                document.getElementById('editManager').value = data.manager_id ?? '';
                document.getElementById('editUserModal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    document.getElementById('editUserForm').addEventListener('submit', function (event) {
        event.preventDefault();
        let userId = document.getElementById('editUserId').value;
        let formData = new FormData(this);

        fetch(`/user-management/${userId}`, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload();
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<script>
    function openEditModal(userId) {
        fetch(`/user-management/${userId}/edit`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editUserId').value = data.id;
                document.getElementById('editName').value = data.name;
                document.getElementById('editEmail').value = data.email;
                document.getElementById('editRole').value = data.role;

                document.getElementById('editUserModal').classList.remove('hidden');
            })
            .catch(error => console.error('Error:', error));
    }

    function closeEditModal() {
        document.getElementById('editUserModal').classList.add('hidden');
    }

    document.getElementById('editUserForm').addEventListener('submit', function (event) {
        event.preventDefault();
        let userId = document.getElementById('editUserId').value;

        let formData = {
            name: document.getElementById('editName').value,
            email: document.getElementById('editEmail').value,
            password: document.getElementById('editPassword').value || null,
            role: document.getElementById('editRole').value
        };

        fetch(`/user-management/${userId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                updateUserRow(userId, data.user);
                closeEditModal();
            })
            .catch(error => console.error('Error:', error));
    });

    function updateUserRow(userId, userData) {
        let row = document.querySelector(`tr[data-user-id="${userId}"]`);
        if (row) {
            row.querySelector('.user-name').textContent = userData.name;
            row.querySelector('.user-email').textContent = userData.email;
        }
    }

</script>

