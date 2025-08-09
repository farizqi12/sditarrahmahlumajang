<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Attendance;
use App\Models\AttendanceLocation;
use Carbon\Carbon;

class AttendanceLogicTest extends TestCase
{
    use RefreshDatabase;

    protected $kepalaSekolah;
    protected $guru;
    protected $murid;
    protected $location;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat roles
        $roleKepsek = Role::create(['name' => 'kepala_sekolah']);
        $roleGuru = Role::create(['name' => 'guru']);
        $roleMurid = Role::create(['name' => 'murid']);
        Role::create(['name' => 'admin']); // Buat role admin untuk command

        // Buat user dengan role yang spesifik
        $this->kepalaSekolah = User::factory()->create(['role_id' => $roleKepsek->id]);
        $this->guru = User::factory()->create(['role_id' => $roleGuru->id]);
        $this->murid = User::factory()->create(['role_id' => $roleMurid->id]);

        // Buat lokasi absensi
        $this->location = AttendanceLocation::factory()->create();
        $this->location->roles()->attach($roleKepsek->id);
    }

    /**
     * @test
     */
    public function user_cannot_check_in_on_weekend()
    {
        Carbon::setTestNow(Carbon::parse('next saturday'));

        $response = $this->actingAs($this->kepalaSekolah)->postJson(route('kepala_sekolah.absensi.checkin'), [
            'latitude' => $this->location->latitude,
            'longitude' => $this->location->longitude,
            'location_id' => $this->location->id,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['success' => false, 'message' => 'Absensi tidak dapat dilakukan pada hari Sabtu atau Minggu.']);
        $this->assertDatabaseMissing('attendances', ['user_id' => $this->kepalaSekolah->id]);

        Carbon::setTestNow();
    }

    /**
     * @test
     */
    public function user_can_check_in_on_weekday()
    {
        Carbon::setTestNow(Carbon::parse('next monday')->startOfDay());

        $response = $this->actingAs($this->kepalaSekolah)->postJson(route('kepala_sekolah.absensi.checkin'), [
            'latitude' => $this->location->latitude,
            'longitude' => $this->location->longitude,
            'location_id' => $this->location->id,
        ]);

        $response->assertOk();
        $response->assertJson(['success' => true]);
        $this->assertDatabaseHas('attendances', [
            'user_id' => $this->kepalaSekolah->id,
            'date' => Carbon::today()->toDateString(),
            'status' => 'hadir',
        ]);

        Carbon::setTestNow();
    }

    /**
     * @test
     */
    public function artisan_command_marks_absent_users_as_alpa()
    {
        Carbon::setTestNow(Carbon::parse('next monday')->startOfDay());

        Attendance::factory()->create([
            'user_id' => $this->guru->id,
            'date' => Carbon::today(),
            'status' => 'hadir',
        ]);

        $this->artisan('attendance:mark-absent')->assertSuccessful();

        // DEBUG: Ambil record yang baru dibuat dan tampilkan isinya
        $alpaRecord = Attendance::where('user_id', $this->murid->id)->first();
        
        // Hentikan tes dan tampilkan data. Jika tidak ada record, ini akan menampilkan null.
        dump($alpaRecord ? $alpaRecord->toArray() : 'Record tidak ditemukan.');

        // Untuk sementara, kita akan memvalidasi record ini secara manual.
        $this->assertNotNull($alpaRecord, "Record alpa seharusnya dibuat untuk murid.");
        $this->assertEquals('alpa', $alpaRecord->status);
        $this->assertNull($alpaRecord->location_id);
    }
}