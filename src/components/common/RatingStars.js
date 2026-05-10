import { useState } from 'react';
import { Star } from 'lucide-react';

export function RatingStars({ onSubmit, disabled = false, initialValue = 0 }) {
  const [rating, setRating] = useState(initialValue);
  const [hover, setHover] = useState(0);
  const [submitted, setSubmitted] = useState(false);

  const handleClick = (value) => {
    if (disabled || submitted) return;
    setRating(value);
    setSubmitted(true);
    onSubmit?.(value);
  };

  return (
    <div className="flex gap-1">
      {[1, 2, 3, 4, 5].map((star) => (
        <button
          key={star}
          type="button"
          disabled={disabled || submitted}
          onClick={() => handleClick(star)}
          onMouseEnter={() => !disabled && !submitted && setHover(star)}
          onMouseLeave={() => setHover(0)}
          className={`${disabled || submitted ? 'cursor-default' : 'cursor-pointer'} transition`}
        >
          <Star
            size={24}
            className={
              star <= (hover || rating)
                ? 'fill-dstudio-gold text-dstudio-gold'
                : 'text-gray-300'
            }
          />
        </button>
      ))}
    </div>
  );
}

export function StaticRatingStars({ value }) {
  return (
    <div className="flex gap-1">
      {[1, 2, 3, 4, 5].map((star) => (
        <Star
          key={star}
          size={20}
          className={
            star <= value
              ? 'fill-dstudio-gold text-dstudio-gold'
              : 'text-gray-300'
          }
        />
      ))}
    </div>
  );
}
