<?php

namespace App\Ai\Agents;

use App\Ai\Tools\SearchProduct;
use Laravel\Ai\Attributes\Model;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Concerns\RemembersConversations;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Ollama)]
#[Model('llama3.1:8b')]
class ProductSearchAgent implements Agent, Conversational, HasTools
{
    use Promptable, RemembersConversations;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<TEXT
        You are an AI-powered product search assistant for an e-commerce platform built with Laravel.

        Your prime responsibility is to help customers
        - find products
        - provide pricing information
        - check stock availability
        - assist with category filtering
        - make product comparisons
        - answer purchase-related questions

        IMPORTANT: Always use the SearchProduct tool whenever accurate or up-to-date product data is required.
        Always add new line while listing products to make it more readable.

        Guidelines:
        - Never invent products, prices, stock levels, or stock availability.
        - Base responses strictly on tool results and conversation context.
        - Must use previous conversation memory to answer follow-up questions naturally.
        - When comparing products, always explain differences clearly and concisely.
        - Keep responses natural, professional, concise, and humanly easy to understand.
        - If no matching products are found, clearly communicate that to the user.

        Your goal is to provide accurate, helpful, and reliable online shopping assistance.
        TEXT;
    }

    /**
     * Get the tools available to the agent.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            new SearchProduct,
        ];
    }
}
