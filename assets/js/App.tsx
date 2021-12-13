import React from "react";
import {Container, Nav, Navbar} from "react-bootstrap";
import {BrowserRouter as Router, Link, NavLink, Route, Routes} from "react-router-dom";
import Courses from "./components/Courses";
import Course from "./components/Course";

const App = () => {
    return (
        <Router>
            <Navbar fixed="top" bg="dark" variant="dark" expand="lg">
                <Container>
                    <Link to="/" style={{ textDecoration: 'none' }}>
                        <Navbar.Brand>Course system</Navbar.Brand>
                    </Link>
                    <Navbar.Toggle aria-controls="basic-navbar-nav" />
                    <Navbar.Collapse id="basic-navbar-nav">
                        <Nav className="me-auto">
                            <Nav.Link as={NavLink} to="/">
                                All courses
                            </Nav.Link>
                            <Nav.Link as={NavLink} to="/enrolled-courses">
                                Enrolled courses
                            </Nav.Link>
                            <Nav.Link as={NavLink} to="/created-courses">
                                Created courses
                            </Nav.Link>
                            <Nav.Link as={NavLink} to="/moderated-courses">
                                Moderated courses
                            </Nav.Link>
                        </Nav>
                    </Navbar.Collapse>
                </Container>
            </Navbar>
            <Container style={{ marginTop: '80px' }}>
                <Routes>
                    <Route path="/" element={<Courses title="Courses" showCreate={true} indexUrl={'/courses'} />} />
                    <Route path="/courses/:courseId" element={<Course />} />
                    <Route path="/enrolled-courses" element={
                            <Courses key={2} title="Enrolled courses" showCreate={false} indexUrl="/enrolled-courses" />
                        }
                    />
                    <Route path="/created-courses" element={
                            <Courses key={3} title="Created courses" showCreate={true} indexUrl="/created-courses" />
                        }
                    />
                    <Route path="/moderated-courses" element={
                            <Courses key={4} title="Moderated courses" showCreate={false} indexUrl="/moderated-courses" />
                        }
                    />
                </Routes>
            </Container>
        </Router>
    )
};

export default App;