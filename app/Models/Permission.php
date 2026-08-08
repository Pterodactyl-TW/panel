<?php

namespace Pterodactyl\Models;

use Illuminate\Support\Collection;

class Permission extends Model
{
    /**
     * The resource name for this model when it is transformed into an
     * API representation using fractal.
     */
    public const RESOURCE_NAME = 'subuser_permission';

    /**
     * Constants defining different permissions available.
     */
    public const ACTION_WEBSOCKET_CONNECT = 'websocket.connect';
    public const ACTION_CONTROL_CONSOLE = 'control.console';
    public const ACTION_CONTROL_START = 'control.start';
    public const ACTION_CONTROL_STOP = 'control.stop';
    public const ACTION_CONTROL_RESTART = 'control.restart';

    public const ACTION_DATABASE_READ = 'database.read';
    public const ACTION_DATABASE_CREATE = 'database.create';
    public const ACTION_DATABASE_UPDATE = 'database.update';
    public const ACTION_DATABASE_DELETE = 'database.delete';
    public const ACTION_DATABASE_VIEW_PASSWORD = 'database.view_password';

    public const ACTION_SCHEDULE_READ = 'schedule.read';
    public const ACTION_SCHEDULE_CREATE = 'schedule.create';
    public const ACTION_SCHEDULE_UPDATE = 'schedule.update';
    public const ACTION_SCHEDULE_DELETE = 'schedule.delete';

    public const ACTION_USER_READ = 'user.read';
    public const ACTION_USER_CREATE = 'user.create';
    public const ACTION_USER_UPDATE = 'user.update';
    public const ACTION_USER_DELETE = 'user.delete';

    public const ACTION_BACKUP_READ = 'backup.read';
    public const ACTION_BACKUP_CREATE = 'backup.create';
    public const ACTION_BACKUP_DELETE = 'backup.delete';
    public const ACTION_BACKUP_DOWNLOAD = 'backup.download';
    public const ACTION_BACKUP_RESTORE = 'backup.restore';

    public const ACTION_ALLOCATION_READ = 'allocation.read';
    public const ACTION_ALLOCATION_CREATE = 'allocation.create';
    public const ACTION_ALLOCATION_UPDATE = 'allocation.update';
    public const ACTION_ALLOCATION_DELETE = 'allocation.delete';

    public const ACTION_FILE_READ = 'file.read';
    public const ACTION_FILE_READ_CONTENT = 'file.read-content';
    public const ACTION_FILE_CREATE = 'file.create';
    public const ACTION_FILE_UPDATE = 'file.update';
    public const ACTION_FILE_DELETE = 'file.delete';
    public const ACTION_FILE_ARCHIVE = 'file.archive';
    public const ACTION_FILE_SFTP = 'file.sftp';

    public const ACTION_STARTUP_READ = 'startup.read';
    public const ACTION_STARTUP_UPDATE = 'startup.update';
    public const ACTION_STARTUP_DOCKER_IMAGE = 'startup.docker-image';

    public const ACTION_SETTINGS_RENAME = 'settings.rename';
    public const ACTION_SETTINGS_REINSTALL = 'settings.reinstall';

    public const ACTION_ACTIVITY_READ = 'activity.read';

    /**
     * Should timestamps be used on this model.
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     */
    protected $table = 'permissions';

    /**
     * Fields that are not mass assignable.
     */
    protected $guarded = ['id', 'created_at', 'updated_at'];

    /**
     * Cast values to correct type.
     */
    protected $casts = [
        'subuser_id' => 'integer',
    ];

    public static array $validationRules = [
        'subuser_id' => 'required|numeric|min:1',
        'permission' => 'required|string',
    ];

    /**
     * All the permissions available on the system. You should use self::permissions()
     * to retrieve them, and not directly access this array as it is subject to change.
     *
     * @see \Pterodactyl\Models\Permission::permissions()
     */
    protected static array $permissions = [
        'websocket' => [
            'description' => '允許使用者連接伺服器的 websocket，讓他們能檢視主控台輸出與即時伺服器狀態。',
            'keys' => [
                'connect' => '允許使用者連接伺服器的 websocket 實例，以串流主控台內容。',
            ],
        ],

        'control' => [
            'description' => '控管使用者是否能控制伺服器的電源狀態，或傳送指令的權限。',
            'keys' => [
                'console' => '允許使用者透過主控台向伺服器實例傳送指令。',
                'start' => '允許使用者在伺服器停止時將其啟動。',
                'stop' => '允許使用者在伺服器執行中時將其停止。',
                'restart' => '允許使用者重新啟動伺服器。這讓他們能在伺服器離線時將其啟動，但不能讓伺服器維持在完全停止的狀態。',
            ],
        ],

        'user' => [
            'description' => '允許使用者管理伺服器上其他子使用者的權限。他們永遠無法編輯自己的帳號，也無法指派自己沒有的權限。',
            'keys' => [
                'create' => '允許使用者為伺服器建立新的子使用者。',
                'read' => '允許使用者檢視伺服器的子使用者及其權限。',
                'update' => '允許使用者修改其他子使用者。',
                'delete' => '允許使用者從伺服器刪除子使用者。',
            ],
        ],

        'file' => [
            'description' => '控管使用者是否能修改此伺服器檔案系統的權限。',
            'keys' => [
                'create' => '允許使用者透過 Panel 或直接上傳建立額外的檔案與資料夾。',
                'read' => '允許使用者檢視目錄內容，但無法檢視或下載檔案內容。',
                'read-content' => '允許使用者檢視特定檔案的內容，這也會允許使用者下載檔案。',
                'update' => '允許使用者更新現有檔案或目錄的內容。',
                'delete' => '允許使用者刪除檔案或目錄。',
                'archive' => '允許使用者將目錄內容壓縮成壓縮檔，並解壓縮系統上現有的壓縮檔。',
                'sftp' => '允許使用者連接 SFTP，並使用其他已指派的檔案權限管理伺服器檔案。',
            ],
        ],

        'backup' => [
            'description' => '控管使用者是否能產生與管理伺服器備份的權限。',
            'keys' => [
                'create' => '允許使用者為此伺服器建立新的備份。',
                'read' => '允許使用者檢視此伺服器所有現有的備份。',
                'delete' => '允許使用者從系統中移除備份。',
                'download' => '允許使用者下載伺服器的備份。危險：這將允許使用者存取備份中此伺服器的所有檔案。',
                'restore' => '允許使用者還原伺服器的備份。危險：這將允許使用者在過程中刪除此伺服器的所有檔案。',
            ],
        ],

        // Controls permissions for editing or viewing a server's allocations.
        'allocation' => [
            'description' => '控管使用者是否能修改此伺服器連接埠配置的權限。',
            'keys' => [
                'read' => '允許使用者檢視目前指派給此伺服器的所有連接埠配置。對此伺服器擁有任何層級存取權限的使用者，永遠都能檢視主要配置。',
                'create' => '允許使用者為伺服器指派額外的連接埠配置。',
                'update' => '允許使用者變更伺服器的主要連接埠配置，並為每個配置附加備註。',
                'delete' => '允許使用者從伺服器刪除連接埠配置。',
            ],
        ],

        // Controls permissions for editing or viewing a server's startup parameters.
        'startup' => [
            'description' => '控管使用者是否能檢視此伺服器啟動參數的權限。',
            'keys' => [
                'read' => '允許使用者檢視伺服器的啟動變數。',
                'update' => '允許使用者修改伺服器的啟動變數。',
                'docker-image' => '允許使用者修改執行伺服器時所使用的 Docker 映像檔。',
            ],
        ],

        'database' => [
            'description' => '控管使用者對此伺服器資料庫管理的存取權限。',
            'keys' => [
                'create' => '允許使用者為此伺服器建立新的資料庫。',
                'read' => '允許使用者檢視與此伺服器關聯的資料庫。',
                'update' => '允許使用者重設資料庫實例的密碼。若使用者沒有 view_password 權限，將無法看到更新後的密碼。',
                'delete' => '允許使用者從此伺服器移除資料庫實例。',
                'view_password' => '允許使用者檢視與此伺服器資料庫實例關聯的密碼。',
            ],
        ],

        'schedule' => [
            'description' => '控管使用者對此伺服器排程管理的存取權限。',
            'keys' => [
                'create' => '允許使用者為此伺服器建立新的排程。', // task.create-schedule
                'read' => '允許使用者檢視此伺服器的排程及其相關工作。', // task.view-schedule, task.list-schedules
                'update' => '允許使用者更新此伺服器的排程與排程工作。', // task.edit-schedule, task.queue-schedule, task.toggle-schedule
                'delete' => '允許使用者刪除此伺服器的排程。', // task.delete-schedule
            ],
        ],

        'settings' => [
            'description' => '控管使用者對此伺服器設定的存取權限。',
            'keys' => [
                'rename' => '允許使用者重新命名此伺服器並變更其描述。',
                'reinstall' => '允許使用者觸發此伺服器的重新安裝。',
            ],
        ],

        'activity' => [
            'description' => '控管使用者對伺服器活動紀錄的存取權限。',
            'keys' => [
                'read' => '允許使用者檢視伺服器的活動紀錄。',
            ],
        ],
    ];

    /**
     * Returns all the permissions available on the system for a user to
     * have when controlling a server.
     */
    public static function permissions(): Collection
    {
        return Collection::make(self::$permissions);
    }
}
