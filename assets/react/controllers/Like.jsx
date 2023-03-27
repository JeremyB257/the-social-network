import axios from 'axios';
import React, { useState } from 'react';

export default function ({ id, isLiked }) {
    const [liked, setLiked] = useState(isLiked);
    const [show, setShow] = useState(false);

    const like = () => {
        axios.get(`/like/${id}`).then(response => {
            // console.log(response.data);
            setLiked(!liked);
        });
    }

    return (
        <div className="mt-2">
            <div className="flex gap-3">
                <button className="text-blue-300 text-sm cursor-pointer" onClick={() => like()}>
                    {!liked && 'Like ♡'}
                    {liked && 'Unlike ♥'}
                </button>
                <button className="text-blue-300 text-sm cursor-pointer" onClick={() => setShow(!show)}>
                    Commenter
                </button>
            </div>

            {show && <div>
                <form className="mt-3">
                    <div className="flex items-end">
                        <div className="flex-grow">
                            <label htmlFor="content" className="block text-gray-800 text-sm font-semibold required">Commentaire</label>
                            <input type="text" id="content" name="content" className="mt-1 w-full rounded-lg border-gray-200" />
                        </div>
                        <div className="ml-4">
                            <button className="inline-block px-6 py-2 bg-blue-500 hover:bg-blue-600 rounded text-white shadow-md duration-300">Publier</button>
                        </div>
                    </div>
                </form>
            </div>}
        </div>
    );
}
