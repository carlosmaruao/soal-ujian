import { useEffect, useState } from "react";

export default function Timer({
  setTimeOut,
  questionNumber,
  data,
  setQuestionNumber,
  setSalah,
}) {
  const [timer, setTimer] = useState(30);

  useEffect(() => {
    // if (timer === 0) return setTimeOut(true);
    if (questionNumber <= data.length) {
      if (timer === 0) {
        setSalah((prev) => prev + 1);
        if (questionNumber === data.length) {
          setTimeOut(true);
        } else {
          setQuestionNumber((prev) => prev + 1);
        }
      }
    }

    const interval = setInterval(() => {
      setTimer((prev) => prev - 1);
    }, 1000);
    return () => clearInterval(interval);
  }, [timer, setTimeOut]);

  useEffect(() => {
    setTimer(30);
  }, [questionNumber]);

  return timer;
}
