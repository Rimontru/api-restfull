import { Component } from '@angular/core';

@Component({
	selector: 'videojuegos',
	templateUrl: './videojuegos.component.html'
})

export class VideojuegosComponent {

	/*se declara el tipo de dato por variable*/
	public nombre:string;
	public game:string;
	public game_retro:string;
	public show:boolean;
	public entero:number;
	public all_games:Array<any>;

	/*se asigna el valor a cada propiedad*/
	constructor() {
		this.nombre = 'videojuegos 2018';
		this.game = 'GTA V';
		this.game_retro = 'Super mario 64';
		this.show = false;
		this.entero = 2018;
		this.all_games = [
			'Los simpsons',
			'Assine creed',
			'GTA V',
			'Halo 2',
			'Mario bros'
		];
	}


}