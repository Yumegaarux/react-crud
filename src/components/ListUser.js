import axios from "axios";
import { useEffect, useState } from "react";
import { Link } from "react-router-dom";


function ListUser() {
    const [users, setUsers] = useState([]);
    useEffect(() => {
        getUsers();
    } , []);


    function getUsers(){
        axios.get('http://localhost/reactCrud/react-crud/api/user').then(function(response){
            setUsers(response.data);
        });
    }

    return(
        <div>
            <h1>List User</h1>
            <table>
                <thead>
                    <tr>
                        <th>id</th>
                        <th>fname</th>
                        <th>lname</th>
                        <th>mobilenum</th>
                    </tr>
                </thead>
                <tbody>
                    {users.map((user) =>
                        <tr key={user.id}>
                            <td>{user.id}</td>
                            <td>{user.fname}</td>
                            <td>{user.lname}</td>
                            <td>{user.mobilenum}</td>

                            <Link to={`user/${user.id}/edit`}>Edit</Link>
                            <button>delete</button>
                        </tr>
                    )}
                </tbody>
            </table>
        </div>
    );
}

export default ListUser