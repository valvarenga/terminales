<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdministrationDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_counts_only_searches_inside_selected_period(): void
    {
        DB::table('route_searches')->insert([
            ['origin_id'=>1,'destination_id'=>2,'origin_name'=>'Somoto','destination_name'=>'Estelí','result_count'=>2,'created_at'=>now()],
            ['origin_id'=>1,'destination_id'=>3,'origin_name'=>'Somoto','destination_name'=>'León','result_count'=>0,'created_at'=>now()],
            ['origin_id'=>1,'destination_id'=>3,'origin_name'=>'Somoto','destination_name'=>'León','result_count'=>0,'created_at'=>now()->subDays(40)],
        ]);
        $this->withSession(['admin_authenticated'=>true])->get('/admin?days=30')->assertOk()
            ->assertViewHas('totalSearches', 2)->assertViewHas('emptySearches', 1)
            ->assertSee('50%')->assertSee('Somoto')->assertSee('León');
        $this->get('/admin?days=90')->assertOk()->assertViewHas('totalSearches', 3);
    }

    public function test_search_statistics_deduplicate_refresh_and_track_missing_routes(): void
    {
        DB::table('municipios')->insert([
            ['id'=>1,'nombre'=>'Somoto','slug'=>'somoto'], ['id'=>2,'nombre'=>'León','slug'=>'leon'],
        ]);
        $url = '/rutas?origen_id=1&destino_id=2';
        $this->get($url)->assertOk();
        $this->get($url)->assertOk();
        $this->assertDatabaseCount('route_searches', 1);
        $this->assertDatabaseHas('route_searches', ['origin_name'=>'Somoto','destination_name'=>'León','result_count'=>0]);
        $this->travel(61)->seconds();
        $this->get($url)->assertOk();
        $this->assertDatabaseCount('route_searches', 2);
    }

    public function test_account_creation_login_and_disable_prevents_reuse(): void
    {
        $this->withSession(['admin_authenticated'=>true,'admin_role'=>'admin','admin_actor'=>'Principal'])
            ->post('/admin/usuarios', ['name'=>'Editor de rutas','email'=>'EDITOR@example.test','role'=>'editor','is_active'=>1,'password'=>'RoutesPassword123','password_confirmation'=>'RoutesPassword123'])
            ->assertRedirect(route('admin.users.index'));
        $user = User::where('email','editor@example.test')->firstOrFail();
        $this->assertTrue(Hash::check('RoutesPassword123', $user->password));
        $this->get('/admin/usuarios')->assertOk()->assertSee('Editor de rutas');
        $this->get(route('admin.users.edit',$user))->assertOk()->assertDontSee('RoutesPassword123');
        $this->post('/admin/logout');
        $this->post('/admin/login', ['username'=>'editor@example.test','password'=>'RoutesPassword123'])->assertRedirect(route('admin.dashboard'));
        $this->get('/admin')->assertOk()->assertDontSee('Usuarios y permisos');
        $this->get('/admin/usuarios')->assertForbidden();
        $this->get('/admin/historial')->assertForbidden();
        $user->update(['is_active'=>false]);
        $this->get('/admin')->assertRedirect(route('admin.login'));
    }

    public function test_history_displays_before_after_and_accepts_end_date_only(): void
    {
        $user = User::create(['name'=>'Nombre anterior','email'=>'audit@example.test','password'=>Hash::make('RoutesPassword123'),'role'=>'editor']);
        $user->update(['name'=>'Nombre actualizado']);
        $this->withSession(['admin_authenticated'=>true])->get('/admin/historial?entity=users&to='.now()->format('Y-m-d'))
            ->assertOk()->assertSee('Nombre anterior')->assertSee('Nombre actualizado')->assertSee('Antes')->assertSee('Después');
        $this->assertSame(['name'=>'Nombre anterior'], AuditLog::where('action','actualizado')->firstOrFail()->before);
    }

    public function test_admin_cannot_disable_self_and_password_change_invalidates_old_sessions(): void
    {
        $user = User::create(['name'=>'Admin','email'=>'admin@example.test','password'=>Hash::make('RoutesPassword123'),'role'=>'admin']);
        $this->withSession(['admin_authenticated'=>true,'admin_user_id'=>$user->id,'admin_role'=>'admin','admin_session_version'=>1])
            ->put(route('admin.users.update',$user), ['name'=>'Admin','email'=>$user->email,'role'=>'editor','is_active'=>1])->assertSessionHasErrors('role');
        $this->put(route('admin.users.update',$user), ['name'=>'Admin','email'=>$user->email,'role'=>'admin','is_active'=>1,'password'=>'AnotherPassword456','password_confirmation'=>'AnotherPassword456'])->assertRedirect(route('admin.users.index'));
        $this->assertSame(2, $user->fresh()->session_version);
        $this->assertTrue(Hash::check('AnotherPassword456', $user->fresh()->password));
        $this->get('/admin')->assertOk();
    }
}
