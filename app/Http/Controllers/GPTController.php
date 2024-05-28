<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use OpenAI;
class GPTController extends Controller
{

  protected $openAIClient;

  public function __construct()
  {
      $apiKey = env('OPENAI_API_KEY');
      Log::info($apiKey);
      $this->openAIClient = OpenAI::client($apiKey);
  }

  public function chat()
  {
      $result = $this->openAIClient->chat()->create([
          'model' => 'gpt-4o',
          'messages' => [
              ['role' => 'user', 'content' => 'Personalizar 3 GPTs con API GPT4o en Laravel'],
          ],
      ]);
      return response()->json(['response' => $result['choices'][0]['message']['content']]);
  }
}
