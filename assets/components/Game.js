import React, {useState} from "react";
import Board from "./Board";
import Buttons from "./Buttons";
import axios from "axios";
import Swal from "sweetalert2";


const Game = () => {
    const [newGameTwoPlayers, setNewGameTwoPlayers] = useState(true);
    const [newGameVsComputer, setNewGameVsComputer] = useState(true);
    const [currentGameIndex, setCurrentGameIndex] = useState(0);
    const [xWinned, setXWinned] = useState(0);
    const [oWinned, setOWinned] = useState(0);
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

    const incrementXWinned = () => {
        setXWinned(xWinned + 1);
    }

    const incrementOWinned = () => {
        setOWinned(oWinned + 1);
    }

    const incrementDraws = () => {
        setDraws(draws + 1);
    }

    const createNewGame = () => {
        axios.post('/api', {})
            .then(function (response) {
                if (response.data.game_id) {
                    setCurrentGameIndex(response.data.game_id);
                    setXWinned(response.data.xWinned);
                    setOWinned(response.data.oWinned);
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
                        xWinned={xWinned}
                        incrementX={() => incrementXWinned()}
                        oWinned={oWinned}
                        incrementO={() => incrementOWinned()}
                        draws={draws}
                        incrementD={() => incrementDraws()}
                    />
            }
        </div>
    );

}

export default Game;
