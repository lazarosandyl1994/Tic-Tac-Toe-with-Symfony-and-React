import React, {useState} from "react";
import Board from "./Board";
import Buttons from "./Buttons";
import axios from "axios";
import Swal from "sweetalert2";


const Game = () => {
    const [newGame, setNewGame] = useState(true);
    const [playerVs, setPlayerVs] = useState("player");
    const [currentGameIndex, setCurrentGameIndex] = useState(0);
    const [xWon, setXWon] = useState(0);
    const [oWon, setOWon] = useState(0);
    const [draws, setDraws] = useState(0);

    const handleClickGameTwoPlayers = () => {
        createNewGame();
        setPlayerVs("player");
        setNewGame(!newGame);

    }

    const handleClickGameVsComputer = () => {
        createNewGame();
        setPlayerVs("computer");
        setNewGame(!newGame);
    }

    const incrementXWon = () => {
        setXWon(xWon + 1);
    }

    const incrementOWon = () => {
        setOWon(oWon + 1);
    }

    const incrementDraws = () => {
        setDraws(draws + 1);
    }

    const createNewGame = () => {
        axios.post('/api', {})
            .then(function (response) {
                if (response.data.game_id) {
                    setCurrentGameIndex(response.data.game_id);
                    setXWon(response.data.xWon);
                    setOWon(response.data.oWon);
                    setDraws(response.data.draws);
                    setNewGame(false);
                    Swal.fire({
                        icon: 'success',
                        title: 'Juego creado exitosamente',
                        showConfirmButton: true
                    })
                }

            })
            .catch(function (error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al crear el juego!',
                    showConfirmButton: true
                })
            });
    }

    return (
        <div>
            {
                newGame
                    ?
                    <Buttons
                        onClickTwoPlayers={() => handleClickGameTwoPlayers()}
                        onClickVsComputer={() => handleClickGameVsComputer()}
                    />
                    :
                    <Board
                        gameIndex={currentGameIndex}
                        xWon={xWon}
                        incrementX={() => incrementXWon()}
                        oWon={oWon}
                        incrementO={() => incrementOWon()}
                        draws={draws}
                        incrementD={() => incrementDraws()}
                        playVs={playerVs}
                    />
            }
        </div>
    );

}

export default Game;
