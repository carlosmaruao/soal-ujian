import { useEffect, useState } from "react";
import useSound from "use-sound";
import play from "../sounds/play.mp3";
import correct from "../sounds/correct.mp3";
import wrong from "../sounds/wrong.mp3";

export default function Trivia({
  data,
  questionNumber,
  setQuestionNumber,
  setBenar,
  setSalah,
  setTimeOut,
}) {
  const [question, setQuestion] = useState(null);
  const [selectedAnswer, setSelectedAnswer] = useState(null);
  const [className, setClassName] = useState("answer");
  const [letsPlay] = useSound(play);
  const [correctAnswer] = useSound(correct);
  const [wrongAnswer] = useSound(wrong);

  useEffect(() => {
    // letsPlay();
  }, [letsPlay]);

  useEffect(() => {
    let soal = data[questionNumber - 1];
    const shuffledPosts = shuffleArray(soal.answers);
    soal.answers = shuffledPosts;
    setQuestion(soal);
  }, [data, questionNumber]);

  const delay = (duration, callback) => {
    setTimeout(() => {
      callback();
    }, duration);
  };

  const handleClick = (a) => {
    setSelectedAnswer(a);
    setClassName("answer active");
    delay(300, () => {
      setClassName(a.correct ? "answer correct" : "answer wrong");
    });
    // setTimeout(() => {
    //   setClassName(a.correct ? "answer correct" : "answer wrong");
    // }, 3000);

    // setTimeout(() => {
    delay(1000, () => {
      // if (a.correct) {
      // correctAnswer();  correct sound
      delay(1000, () => {
        if (questionNumber <= data.length) {
          if (a.correct) {
            setBenar((prev) => prev + 1);
          } else {
            setSalah((prev) => prev + 1);
          }

          if (questionNumber === data.length) {
            setTimeOut(true);
          } else {
            setQuestionNumber((prev) => prev + 1);
            setSelectedAnswer(null);
          }
        } else {
          setTimeOut(true);
        }
      });
      // setTimeout(() => {
      //   setQuestionNumber((prev) => prev + 1);
      //   setSelectedAnswer(null);
      // }, 1000);
      // } else {
      // wrongAnswer(); //error sound
      // delay(1000, () => {
      //   setTimeOut(true);
      // });
      // setTimeout(() => {
      //   setTimeOut(true);
      // }, 1000);
      // }
      // }, 5000);
    });
  };

  function shuffleArray(array) {
    let i = array.length - 1;
    for (; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      const temp = array[i];
      array[i] = array[j];
      array[j] = temp;
    }
    return array;
  }

  return (
    <div className="trivia">
      <div className="question">{question?.question}</div>
      <div className="answers">
        {question?.answers.map((a) => (
          <div
            key={a.text}
            className={selectedAnswer === a ? className : "answer"}
            onClick={() => !selectedAnswer && handleClick(a)}
          >
            {a.text}
          </div>
        ))}
      </div>
    </div>
  );
}
