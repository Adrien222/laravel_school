<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

class ToolController extends Controller
{
    // Méthode pour afficher tous les outils
    public function index()
    {
        $tools = [
            (object) [
                "id" => 1,
                "name" => "Marteau",
                "description" => "pour frapper et enfoncer des clous dans du bois ou d'autres matériaux.",
                "price" => "23.99",
            ],
            (object) [
                "id" => 2,
                "name" => "Tournevis",
                "description" => "pour serrer ou desserrer les vis.",
                "price" => "15.99",
            ],
            (object) [
                "id" => 3,
                "name" => "Scie",
                "description" => "pour couper le bois, le métal ou d'autres matériaux.",
                "price" => "56.33",
            ],
        ];

        return view('tools.index', compact('tools'));
    }

    // Méthode pour afficher un outil spécifique basé sur son id
    public function show(Tool $tool)
    {

        return view('tools.show', compact('tool'));
    }

    public function testCast()
    {
        // Trouver un outil existant
        $tool = Tool::find(1);

        // Afficher le prix actuel
        dd("Prix actuel : " . $tool->price);
    }

}
