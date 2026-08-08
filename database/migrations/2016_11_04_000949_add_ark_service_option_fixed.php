<?php

use Illuminate\Database\Migrations\Migration;

class AddArkServiceOptionFixed extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::transaction(function () {
            $service = DB::table('services')->select('id')->where('author', 'ptrdctyl-v040-11e6-8b77-86f30ca893d3')->where('name', 'Source Engine')->first();

            // 沒有 SRCDS 服務，略過
            if (!$service) {
                return;
            }

            // 此服務選項已安裝。
            if (DB::table('service_options')->select('id')->where('name', 'Ark: Survival Evolved')->where('parent_service', $service->id)->first()) {
                return;
            }

            $oid = DB::table('service_options')->insertGetId([
                'parent_service' => $service->id,
                'name' => 'Ark: Survival Evolved',
                'description' => '身為一名流落荒島、赤身裸體、又凍又餓的男人或女人，在這座名為 ARK 的神秘島嶼的無情海岸上，運用你的技巧與智謀，獵殺、馴服並騎乘充斥全島的巨型恐龍與其他遠古生物。狩獵、採集資源、製作道具、種植作物、研究科技並建造庇護所以抵禦環境並保存貴重物品，同時還要與其他數百名玩家組隊（或互相獵殺）以求生存、稱霸，並逃出生天！（資料來源：Gamepedia ARK）',
                'tag' => 'ark',
                'docker_image' => 'quay.io/pterodactyl/srcds:ark',
                'executable' => './ShooterGameServer',
                'startup' => 'TheIsland?listen?ServerPassword={{ARK_PASSWORD}}?ServerAdminPassword={{ARK_ADMIN_PASSWORD}}?Port={{SERVER_PORT}}?MaxPlayers={{SERVER_MAX_PLAYERS}}',
            ]);

            DB::table('service_variables')->insert([
                'option_id' => $oid,
                'name' => 'Server Password',
                'description' => '若有設定，玩家必須提供此密碼才能加入伺服器。',
                'env_variable' => 'ARK_PASSWORD',
                'default_value' => '',
                'user_viewable' => 1,
                'user_editable' => 1,
                'required' => 0,
                'regex' => '/^(\w\.*)$/',
            ]);

            DB::table('service_variables')->insert([
                'option_id' => $oid,
                'name' => 'Admin Password',
                'description' => '若有設定，玩家必須（透過遊戲內主控台）提供此密碼才能取得伺服器管理員指令的存取權限。',
                'env_variable' => 'ARK_ADMIN_PASSWORD',
                'default_value' => '',
                'user_viewable' => 1,
                'user_editable' => 1,
                'required' => 0,
                'regex' => '/^(\w\.*)$/',
            ]);

            DB::table('service_variables')->insert([
                'option_id' => $oid,
                'name' => 'Maximum Players',
                'description' => '指定伺服器上可同時遊玩的最大玩家人數。',
                'env_variable' => 'SERVER_MAX_PLAYERS',
                'default_value' => 20,
                'user_viewable' => 1,
                'user_editable' => 1,
                'required' => 1,
                'regex' => '/^(\d{1,4})$/',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::transaction(function () {
            $service = DB::table('services')->select('id')->where('author', 'ptrdctyl-v040-11e6-8b77-86f30ca893d3')->where('name', 'Source Engine')->first();

            if ($service) {
                $option = DB::table('service_options')->where('parent_service', $service->id)->where('tag', 'ark')->first();

                if ($option) {
                    $variables = DB::table('service_variables')->where('option_id', $option->id)->delete();
                }
            }
        });
    }
}
