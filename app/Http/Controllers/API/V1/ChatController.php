<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ChatResource;
use App\Http\Responses\CustomResponse;
use App\Models\Chat;
use App\Models\Task;
use App\Models\User;
use App\Services\ChatBotService;
use Illuminate\Http\Request;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\BodyParam;
use Knuckles\Scribe\Attributes\Group;
use Knuckles\Scribe\Attributes\UrlParam;

#[Group("AI-Chatbot", "Start chat with AI.")]
class ChatController extends Controller
{
    /**
     * 
     * @var ChatBotService
     */
    protected $chatBotService;

    /**
     * 
     * @var array 
     */
    protected $MSGS;

    /**
     * 
     * @param \App\Services\ChatBotService $chatBotService
     */
    public function __construct(ChatBotService $chatBotService)
    {
        $this->chatBotService = $chatBotService;
        $this->MSGS = [
            "INIT_MSG" => "Read this tasks, each one was parsed as JSON with attributes : title, description, status,content and the date it created at.Ignore message in case there are to tasks",
            "SUG_PRIO_MSG" => "Suggest new priorities if needed for my tasks (If exist any) depending on their importance." ,
        ];
    }

    /**
     * Get chat messages
     * 
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function show($user_id): CustomResponse
    {
        $user = User::find($user_id);
        $chat = Chat::firstOrCreate(['user_id' => $user->user_id], ['title' => 'New chat.']);

        return CustomResponse::ok(new ChatResource($chat));
    }

    /**
     * Send msg.
     * 
     * @param \App\Models\User $user
     * @param \Illuminate\Http\Request $request
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    #[BodyParam('content', 'string', "This is user message for AI", true, "Hello AI, I am Dev Ghazy")]
    public function sendMessage($user_id, Request $request)
    {
        $user = User::find($user_id);
        //
        $chat = Chat::firstOrCreate(['user_id' => $user->user_id], ['title' => 'New chat.']);

        // 
        $bot_message = $this->chatBotService->sendMessage(
            chat_id: $chat->chat_id,
            content: $request->content == "SUG_PRIO_MSG" ? $this->MSGS["SUG_PRIO_MSG"] : $request->content,
            prefix_content: $this->prefixContent($user)
        );

        //
        return CustomResponse::ok($bot_message);
    }

    /**
     * 
     * @param \App\Models\User $user
     * @return array{content: bool|string, role: string[]}
     */
    public function prefixContent(User $user)
    {
        //
        $prefix_content = [];
        $tasks = Task::where('user_id' , $user->user_id)->get();
        if(!$tasks->count()) {
            return $prefix_content ; 
        }
        
        $prefix_content[] = [
            "role" => "user",
            "content" => $this->MSGS["INIT_MSG"]
        ];

        foreach ($tasks as $task) {
            $prefix_content[] = [
                'role' => "user",
                'content' => json_encode([
                    'title' => $task->title,
                    'content' => $task->content,
                    'priority' => $task->priority,
                    'status' => $task->status,
                    'created_at' => $task->created_at
                ])
            ];
        }

        return $prefix_content;
    }

    /**
     * Delete chat 
     * 
     * @param \App\Models\User $user
     * @return CustomResponse
     */
    #[Authenticated]
    #[UrlParam('user_id', "string", "User uuid", example: "01968c0f-6593-71a6-a1e4-9ff2714fe9ea")]
    public function deleteChat($user_id): CustomResponse
    {
        $user = User::find($user_id);
        $chat = Chat::where('user_id', $user->user_id)->first();

        $chat->delete();

        return CustomResponse::deleted();
    }
}
