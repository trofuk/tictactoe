import React from 'react';
import axios from 'axios';
import Board from "./Board";
import cancel from '../assets/img/cancel.svg';

class Game extends React.Component {

    winnerStatus = {
        "X_WON": "Congratulation, you won",
        "O_WON": "Server is a winner. Try once more"
    };

    constructor(props) {
        super(props);
        this.state = {
            board: {
                squares: Array(9).fill('-'),
                status: '',
                uuid: ''
            },
            games: [],
            stepNumber: 0,
        };
    }

    componentDidMount() {
        axios.get(`http://localhost:8000/games/`)
            .then((response) => {
                this.setState({
                    games: response.data.data,
                });
            });

    }

    handleClick(i) {
        ;
        const squares = this.state.board.squares.slice();
        squares[i] = "X";
        const data = {
            board: squares.join('')
        };
        if (!this.state.board.status) {
            axios.post(`http://localhost:8000/games/`, JSON.stringify(data))
                .then((response) => {
                    const {
                        data
                    } = response;
                    this.setState({
                        board: {
                            squares: Array.from(data.game.board),
                            status: data.game.status,
                            uuid: data.game.uuid
                        }
                    });
                })
        } else {
            const data = {
                board: squares.join(''),
                status: this.state.board.status,
                uuid: this.state.board.uuid
            };

            axios.put(`http://localhost:8000/games/` + this.state.board.uuid, JSON.stringify(data))
                .then((response) => {
                    const {
                        data
                    } = response;
                    if (data.game.status !== this.state.board.status) {
                        alert(`${this.winnerStatus[data.game.status]}`)
                    }
                    this.setState({
                        board: {
                            squares: Array.from(data.game.board),
                            status: data.game.status,
                            uuid: data.game.uuid
                        },
                    });
                })
        }

    }

    jumpTo(id) {
        axios.get(`http://localhost:8000/games/` + id)
            .then((response) => {
                const {
                    data
                } = response;
                this.setState({
                    board: {
                        squares: Array.from(data.game.board),
                        status: data.game.status,
                        uuid: data.game.uuid
                    },
                });
            });
    }


    deleteGame(id) {
        axios.delete('http://localhost:8000/games/' + id)
            .then(response => {
                const filteredGames = this.state.games.filter(obj => obj.uuid !== id);
                this.setState({
                    games: filteredGames
                });
            })
    }

    render() {
        const gamesHistory = this.state.games;
        const current = this.state.board;
        const games = gamesHistory.map((obj) => {
            return ( <li key = {obj.uuid} >
                        <button className = "list-button"
                            onClick = {
                            () => this.jumpTo(obj.uuid)
                        } >
                            {obj.status}
                            </button>
                            <img className = "cancel-button"
                            src = {cancel}
                            onClick = {() => this.deleteGame(obj.uuid)}
                            alt = {cancel}/>
                    </li >);
        });

        const startGame = () => {
            if (this.state.board.status) {
                return ''
            }
            return ( <div> To start new game click on square </div>);
        };

        return ( <div className = "game" >
                    <div className = "game-board" > {
                        startGame()
                    }
                    < Board squares = {current.squares}
                        onClick = { i => this.handleClick(i) }
                />
                </div>
                <div className = "game-info" >
                    < ol > {games} < /ol>
                </div>
            </div>);
            }
    }

export default Game;