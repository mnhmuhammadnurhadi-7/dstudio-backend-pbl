import { useState } from 'react';
import { Star } from 'lucide-react';

export function RatingStars({ onSubmit, disabled = false, initialValue = 0 }) {
  const [rating, setRating] = useState(initialValue);
  const [hover, setHover] = useState(0);
  const [submitted, setSubmitted] = useState(false);
  const [ulasan, setUlasan] = useState('');

  const handleClick = (value) => {
    if (disabled || submitted) return;
    setRating(value);
  };

  const handleSubmit = () => {
    if (disabled || submitted || rating === 0) return;
    setSubmitted(true);
    onSubmit?.({ nilai_rating: rating, ulasan });
  };

  return (
    <div className="space-y-4">
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
      
      {rating > 0 && !submitted && (
        <div className="space-y-2">
          <textarea
            value={ulasan}
            onChange={(e) => setUlasan(e.target.value)}
            placeholder="Tulis ulasan (opsional)"
            className="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-dstudio-gold"
            rows={3}
            disabled={disabled}
          />
          <button
            onClick={handleSubmit}
            disabled={disabled}
            className="bg-dstudio-gold text-dstudio-dark px-4 py-2 rounded-lg font-semibold hover:bg-yellow-500 disabled:opacity-50"
          >
            Kirim Rating
          </button>
        </div>
      )}
      
      {submitted && (
        <p className="text-green-600 font-medium">Terima kasih atas rating Anda!</p>
      )}
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
