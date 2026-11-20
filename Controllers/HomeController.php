<?php

class HomeController extends Controller
{
    public function index(): void
    {
        $data = [
            "page_title" => "Inicio",
            "estado" => $this->model->estadoConexion(),
        ];
        $this->view("Home/index", $data);
    }
}
