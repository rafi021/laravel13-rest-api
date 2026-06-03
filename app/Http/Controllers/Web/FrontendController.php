<?php

namespace App\Http\Controllers\Web;

use App\Ai\Agents\ProductSearchAgent;
use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Laravel\Ai\Responses\StreamableAgentResponse;

class FrontendController extends Controller
{
    public function index(): View
    {
        return view('welcome');
    }

    public function show(Post $post): View
    {
        $post->loadMissing('category');

        return view('post-details', compact('post'));
    }

    public function chat(): View
    {
        return view('chat');
    }

    public function stream(Request $request)
    {
        $validated = $request->validate([
            'query' => ['required', 'string', 'max:4000'],
        ]);

        $user = $request->user();

        $conversationId = $this->resolveConversation($user);

        $stream = $conversationId
            ? (new ProductSearchAgent())
            ->continue($conversationId, as: $user)
            ->stream((string)$validated['query'])
            : (new ProductSearchAgent())
            ->forUser($user)
            ->stream((string)$validated['query']);

        return $stream->then(function (StreamableAgentResponse $response) {
            if ($response->conversationId) {
                session([
                    'product_agent_conversation_id' => $response->conversationId,
                ]);
            }
        });
    }

    protected function resolveConversation(User $user): ?string
    {
        return session('product_agent_conversation_id')
            ?? DB::table('agent_conversations')
            ->where('user_id', $user->id)
            ->latest('updated_at')
            ->value('id');
    }
}
