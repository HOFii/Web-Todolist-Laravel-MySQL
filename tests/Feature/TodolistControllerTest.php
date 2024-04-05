<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodolistControllerTest extends TestCase
{
    public function testTodolist()
    {
        $this->withSession([
            "user" => "akbar",
            "todolist" => [
                [
                    "id" => "1",
                    "todo" => "Gusti"
                ],
                [
                    "id" => "2",
                    "todo" => "Elaina"
                ]
            ]
        ])->get('/todolist')
            ->assertSeeText("1")
            ->assertSeeText("Gusti")
            ->assertSeeText("2")
            ->assertSeeText("Elaina");
    }

    public function testAddTodoFailed()
    {
        $this->withSession([
            "user" => "akbar"
        ])->post("/todolist", [])
            ->assertSeeText("Todo is required");
    }

    public function testAddTodoSuccess()
    {
        $this->withSession([
            "user" => "akbar"
        ])->post("/todolist", [
            "todo" => "Gusti"
        ])->assertRedirect("/todolist");
    }

    public function testRemoveTodolist()
    {
        $this->withSession([
            "user" => "khannedy",
            "todolist" => [
                [
                    "id" => "1",
                    "todo" => "Gusti"
                ],
                [
                    "id" => "2",
                    "todo" => "Elaina"
                ]
            ]
        ])->post("/todolist/1/delete")
            ->assertRedirect("/todolist");
    }
}
