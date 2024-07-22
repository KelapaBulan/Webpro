<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
  
class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Mejiro McQueen', 
            'email' => 'uma4@mail.com',
            'password' => bcrypt('password')
        ]);

        $role1 = Role::create(['name' => 'user']);
        $role1->givePermissionTo('product-list');
         
        $user->assignRole([$role1]);
    }
}