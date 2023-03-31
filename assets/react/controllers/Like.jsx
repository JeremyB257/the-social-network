import axios from 'axios';
import React, { useState } from 'react';

export default function ({ id, isLiked, baseComments }) {
    const [liked, setLiked] = useState(isLiked);
    const [show, setShow] = useState(false);

    const [data, setData] = useState({content: ''});
    const [errors, setErrors] = useState({});
    const [success, setSuccess] = useState(false);

    const [comments, setComments] = useState(baseComments);

    const handleChange = (event) => {
        setData({ ...data, ...{ [event.target.name]: event.target.value } });
    }

    const handleSubmit = (event) => {
        event.preventDefault();

        setErrors({});

        axios.post(`/api/comment/create/${id}`, data)
            .then(response => {
                setSuccess(response.data.message);
                setComments([ { ...response.data.comment }, ...comments ]);
                setData({ content: '' });
            })
            .catch(errors => setErrors(errors.response.data));
    }

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
                {success && <p className="text-emerald-500">{success}</p>}
                <form className="mt-3" onSubmit={handleSubmit}>
                    <div className="flex items-end">
                        <div className="flex-grow">
                            <label htmlFor="content" className="block text-gray-800 text-sm font-semibold required">Commentaire</label>
                            <input type="text" id="content" name="content" value={data.content} onChange={handleChange} className="mt-1 w-full rounded-lg border-gray-200" />
                            {errors.content && <p className="text-red-500">{errors.content}</p>}
                        </div>
                        <div className="ml-4">
                            <button className="inline-block px-6 py-2 bg-blue-500 hover:bg-blue-600 rounded text-white shadow-md duration-300">Publier</button>
                        </div>
                    </div>
                </form>

                <div className="mt-4">
                    {comments.map(comment =>
                        <div className="flex mb-4">
                            <img className="w-8 h-8 rounded-full shadow-md mr-3 object-cover" src={comment.avatar} alt={comment.firstname} />

                            <div>
                                <h2 className="font-bold text-gray-800">{comment.firstname}</h2>
                                <p>{comment.content}</p>
                                <p className="text-xs text-gray-400">{comment.created_at}</p>
                            </div>
                        </div>
                    )}
                </div>
            </div>}
        </div>
    );
}
