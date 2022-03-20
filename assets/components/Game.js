import React, {useState} from "react";
import Board from "./Board";
import Buttons from "./Buttons";
import axios from "axios";
import Swal from "sweetalert2";


const Game = () => {
    const [newGameTwoPlayers, setNewGameTwoPlayers] = useState(true);
    const [newGameVsComputer, setNewGameVsComputer] = useState(true);
    const [currentGameIndex, setCurrentGameIndex] = useState(0);
    const [xWon, setXWon] = useState(0);
    const [oWon, setOWon] = useState(0);
    const [draws, setDraws] = useState(0);

    const handleClickGameTwoPlayers = () => {
        if (newGameTwoPlayers) {
            createNewGame();
        } else {
            setNewGameTwoPlayers(!newGameTwoPlayers);
        }

    }

    const handleClickGameVsComputer = () => {
        if (newGameVsComputer) {
            createNewGame();
        } else {
            setNewGameVsComputer(!newGameVsComputer);
        }
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
                    setNewGameTwoPlayers(false);
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
                newGameTwoPlayers && newGameVsComputer
                    ?
                    <Buttons onClickTwoPlayers={() => handleClickGameTwoPlayers()}/>
                    :
                    <Board
                        gameIndex={currentGameIndex}
                        xWon={xWon}
                        incrementX={() => incrementXWon()}
                        oWon={oWon}
                        incrementO={() => incrementOWon()}
                        draws={draws}
                        incrementD={() => incrementDraws()}
                    />
            }
        </div>
    );

}

export default Game;
